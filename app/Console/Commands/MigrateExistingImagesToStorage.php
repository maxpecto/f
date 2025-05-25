<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Items;
use App\Models\Platform;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MigrateExistingImagesToStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:migrate-to-storage {--type=all : Type of images to migrate (all, movies, series, platforms)} {--dry-run : Simulate the migration without moving files or updating DB}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates existing images from public/ old paths to storage/app/public/ and updates database paths.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $type = $this->option('type');
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('DRY RUN active. No files will be moved and no database records will be updated.');
        }

        $migratedCount = 0;
        $skippedCount = 0;
        $errorCount = 0;

        if ($type === 'all' || $type === 'movies') {
            $this->info('Processing movie posters and backdrops for migration...');
            Items::where('type', 'movies')->chunkById(100, function ($movies) use (&$migratedCount, &$skippedCount, &$errorCount, $dryRun) {
                foreach ($movies as $movie) {
                    $this->processItemImageField($movie, 'poster', 'public/assets/movies/poster/', 'assets/movies/poster/', $dryRun, $migratedCount, $skippedCount, $errorCount);
                    $this->processItemImageField($movie, 'backdrop', 'public/assets/movies/backdrop/', 'assets/movies/backdrop/', $dryRun, $migratedCount, $skippedCount, $errorCount);
                }
            });
        }

        if ($type === 'all' || $type === 'series') {
            $this->info('Processing series posters and backdrops for migration...');
            Items::where('type', 'series')->chunkById(100, function ($seriesItems) use (&$migratedCount, &$skippedCount, &$errorCount, $dryRun) {
                foreach ($seriesItems as $series) {
                    $this->processItemImageField($series, 'poster', 'public/assets/series/poster/', 'assets/series/poster/', $dryRun, $migratedCount, $skippedCount, $errorCount);
                    $this->processItemImageField($series, 'backdrop', 'public/assets/series/backdrop/', 'assets/series/backdrop/', $dryRun, $migratedCount, $skippedCount, $errorCount);
                }
            });
        }

        if ($type === 'all' || $type === 'platforms') {
            $this->info('Processing platform logos for migration...');
            Platform::chunkById(100, function ($platforms) use (&$migratedCount, &$skippedCount, &$errorCount, $dryRun) {
                foreach ($platforms as $platform) {
                    $this->processPlatformLogoField($platform, 'logo_image_path', 'public/platform_logos/', 'assets/platforms/', $dryRun, $migratedCount, $skippedCount, $errorCount);
                }
            });
        }

        $this->info(sprintf('Image migration complete. %d migrated, %d skipped, %d errors.', $migratedCount, $skippedCount, $errorCount));
        if ($dryRun) {
            $this->warn('DRY RUN was active. No actual changes were made.');
        }
        return Command::SUCCESS;
    }

    private function processItemImageField($item, $field, $oldPublicBaseDir, $newStorageBaseDir, $dryRun, &$migratedCount, &$skippedCount, &$errorCount)
    {
        $currentPath = $item->{$field};

        if (empty($currentPath) || $currentPath === 'default_poster.jpg' || $currentPath === 'default_backdrop.jpg') {
            // $this->line("Skipping (empty or default): {$field} for item ID {$item->id}");
            return;
        }

        // Check if already new format (e.g., assets/movies/poster/...)
        if (Str::startsWith($currentPath, 'assets/')) {
            if (Storage::disk('public')->exists($currentPath)) {
                // $this->line("Skipping (already new format and exists in storage): {$field} - {$currentPath} for item ID {$item->id}");
                 return; // Already in new format and exists
            } else {
                 // Path looks new but doesn't exist in storage, might be an issue or an old record pointing to a non-existent new path.
                 // Try to find it in the old location.
                 $this->warn("Warning: Path {$currentPath} for item ID {$item->id} seems new but not found in storage. Will attempt to locate in old public path.");
            }
        }
        
        // Determine the old public path and filename
        $filename = basename($currentPath);
        $oldPublicFullPath = public_path(Str::after($oldPublicBaseDir, 'public/') . $filename); // e.g. /home/user/project/public/assets/movies/poster/file.jpg
        $oldStorageLikePublicPath = Str::after($oldPublicBaseDir, 'public/') . $filename; // e.g. assets/movies/poster/file.jpg

        $newRelativePath = $newStorageBaseDir . $filename; // e.g. assets/movies/poster/file.jpg (for Storage::disk('public'))

        if (File::exists($oldPublicFullPath)) {
            $this->line("Found old image: {$oldPublicFullPath}");
            if (!$dryRun) {
                try {
                    // Move original file
                    Storage::disk('public')->put($newRelativePath, File::get($oldPublicFullPath));
                    File::delete($oldPublicFullPath); // Delete after successful copy to storage

                    // Check and move WebP version if exists
                    $originalExtension = pathinfo($filename, PATHINFO_EXTENSION);
                    $webpFilename = Str::replaceLast($originalExtension, 'webp', $filename);
                    $oldPublicWebPFullPath = public_path(Str::after($oldPublicBaseDir, 'public/') . $webpFilename);
                    $newWebPRelativePath = $newStorageBaseDir . $webpFilename;

                    if (File::exists($oldPublicWebPFullPath)) {
                        Storage::disk('public')->put($newWebPRelativePath, File::get($oldPublicWebPFullPath));
                        File::delete($oldPublicWebPFullPath);
                        $this->info("Moved WebP: {$oldPublicWebPFullPath} -> {$newWebPRelativePath}");
                    }
                    
                    $item->{$field} = $newRelativePath;
                    $item->save();
                    $this->info("Migrated and DB updated: {$field} for item ID {$item->id}. New path: {$newRelativePath}");
                    $migratedCount++;

                } catch (\Exception $e) {
                    $this->error("Error migrating {$field} for item ID {$item->id}: " . $e->getMessage());
                    $errorCount++;
                }
            } else {
                $this->info("[Dry Run] Would migrate: {$oldPublicFullPath} -> {$newRelativePath} for item ID {$item->id}");
                $migratedCount++; // Count as if migrated for dry run summary
            }
        } else {
            // Check if the $currentPath was just a filename and it might be in storage/app/public directly (less likely for items based on old logic)
            // Or if it's already in the new storage path but the DB record is old.
            if (Storage::disk('public')->exists($newRelativePath) || Storage::disk('public')->exists($currentPath)) {
                 // $this->line("Skipped (already in storage or target path): {$currentPath} for item ID {$item->id}. Ensuring DB is correct.");
                 if ($item->{$field} !== $newRelativePath && Storage::disk('public')->exists($newRelativePath)) {
                     if(!$dryRun) {
                        $item->{$field} = $newRelativePath;
                        $item->save();
                        $this->info("DB path corrected for item ID {$item->id} to {$newRelativePath}");
                     } else {
                        $this->info("[Dry Run] Would correct DB path for item ID {$item->id} to {$newRelativePath}");
                     }
                     $migratedCount++; // Count as a fix
                 } else {
                    $skippedCount++;
                 }
            } else {
                 // $this->warn("Skipped (original not found at expected old public path): {$oldPublicFullPath} for item ID {$item->id}");
                 $skippedCount++;
            }
        }
    }

    private function processPlatformLogoField($platform, $field, $oldPublicBaseDir, $newStorageBaseDir, $dryRun, &$migratedCount, &$skippedCount, &$errorCount)
    {
        $currentPath = $platform->{$field};

        if (empty($currentPath)) {
            return;
        }

        // Check if already new format (e.g., assets/platforms/...)
        if (Str::startsWith($currentPath, 'assets/platforms/')) {
            if (Storage::disk('public')->exists($currentPath)) {
                // $this->line("Skipping (already new format and exists in storage): {$currentPath} for platform ID {$platform->id}");
                return; 
            } else {
                 $this->warn("Warning: Path {$currentPath} for platform ID {$platform->id} seems new but not found in storage. Will attempt to locate in old public path.");
            }
        }

        // Old paths could be:
        // 1. platform_logos/filename.webp (saved by old PlatformController)
        // 2. assets/platforms/filename.webp (if ProcessExistingImages was run with old logic for platforms)
        // We target moving files from public/platform_logos/ or public/assets/platforms/

        $filename = basename($currentPath);
        $oldPath1 = public_path('platform_logos/' . $filename); // From old controller
        $oldPath2 = public_path('assets/platforms/' . $filename); // if it was somehow in public/assets/platforms

        $actualOldPublicPath = null;
        if (File::exists($oldPath1)) {
            $actualOldPublicPath = $oldPath1;
        } elseif (File::exists($oldPath2)) {
            $actualOldPublicPath = $oldPath2;
        } elseif (File::exists(public_path($currentPath))) { // If currentPath is like 'platform_logos/file.png' directly from DB
            $actualOldPublicPath = public_path($currentPath);
        }


        $newRelativePath = $newStorageBaseDir . $filename; // e.g. assets/platforms/logo.png

        if ($actualOldPublicPath) {
            $this->line("Found old logo: {$actualOldPublicPath}");
            if (!$dryRun) {
                try {
                    Storage::disk('public')->put($newRelativePath, File::get($actualOldPublicPath));
                    File::delete($actualOldPublicPath);

                    // Check and move WebP version if original wasn't WebP
                    $originalExtension = pathinfo($filename, PATHINFO_EXTENSION);
                    if (strtolower($originalExtension) !== 'webp') {
                        $webpFilename = Str::replaceLast($originalExtension, 'webp', $filename);
                        $oldPublicWebPPath1 = public_path('platform_logos/' . $webpFilename);
                        $oldPublicWebPPath2 = public_path('assets/platforms/' . $webpFilename);
                        $newWebPRelativePath = $newStorageBaseDir . $webpFilename;
                        
                        $actualOldWebPPublicPath = null;
                        if (File::exists($oldPublicWebPPath1)) $actualOldWebPPublicPath = $oldPublicWebPPath1;
                        else if (File::exists($oldPublicWebPPath2)) $actualOldWebPPublicPath = $oldPublicWebPPath2;
                        
                        if ($actualOldWebPPublicPath && File::exists($actualOldWebPPublicPath)) {
                             Storage::disk('public')->put($newWebPRelativePath, File::get($actualOldWebPPublicPath));
                             File::delete($actualOldWebPPublicPath);
                             $this->info("Moved WebP: {$actualOldWebPPublicPath} -> {$newWebPRelativePath}");
                        }
                    }
                    
                    $platform->{$field} = $newRelativePath; // Save the path of the ORIGINAL (e.g. .png, .jpg) to DB
                    $platform->save();
                    $this->info("Migrated and DB updated: {$field} for platform ID {$platform->id}. New path: {$newRelativePath}");
                    $migratedCount++;

                } catch (\Exception $e) {
                    $this->error("Error migrating {$field} for platform ID {$platform->id}: " . $e->getMessage());
                    $errorCount++;
                }
            } else {
                $this->info("[Dry Run] Would migrate: {$actualOldPublicPath} -> {$newRelativePath} for platform ID {$platform->id}");
                $migratedCount++;
            }
        } else {
             if (Storage::disk('public')->exists($newRelativePath) || Storage::disk('public')->exists($currentPath)) {
                 // $this->line("Skipped (already in storage or target path): {$currentPath} for platform ID {$platform->id}. Ensuring DB is correct.");
                 if ($platform->{$field} !== $newRelativePath && Storage::disk('public')->exists($newRelativePath) && !Str::endsWith($newRelativePath, '.webp')) { // Ensure we save original path to DB
                     if(!$dryRun) {
                        $platform->{$field} = $newRelativePath;
                        $platform->save();
                        $this->info("DB path corrected for platform ID {$platform->id} to {$newRelativePath}");
                     } else {
                        $this->info("[Dry Run] Would correct DB path for platform ID {$platform->id} to {$newRelativePath}");
                     }
                     $migratedCount++;
                 } else {
                    $skippedCount++;
                 }
            } else {
                // $this->warn("Skipped (original logo not found at expected old public paths): {$currentPath} for platform ID {$platform->id}");
                $skippedCount++;
            }
        }
    }
}
