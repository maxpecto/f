<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Items;
use App\Models\Platform;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class ProcessExistingImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:process {--type=all : Type of images to process (all, movies, series, platforms)} {--force : Overwrite existing WebP images} {--quality=80 : WebP quality (0-100)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process existing images to create optimized WebP versions.';

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
        $force = $this->option('force');
        $quality = (int) $this->option('quality');

        if ($quality < 0 || $quality > 100) {
            $this->error('Quality must be between 0 and 100.');
            return Command::FAILURE;
        }

        $processedCount = 0;
        $skippedCount = 0;

        if ($type === 'all' || $type === 'movies') {
            $this->info('Processing movie posters and backdrops...');
            Items::where('type', 'movies')->chunk(100, function ($movies) use (&$processedCount, &$skippedCount, $force, $quality) {
                foreach ($movies as $movie) {
                    // Process Poster
                    if ($movie->poster && $movie->poster !== 'default_poster.jpg') {
                        $this->processImageField($movie, 'poster', ['width' => 405, 'height' => 600], $force, $quality, $processedCount, $skippedCount);
                    }
                    // Process Backdrop
                    if ($movie->backdrop && $movie->backdrop !== 'default_backdrop.jpg') {
                        $this->processImageField($movie, 'backdrop', ['width' => 1000, 'height' => 600], $force, $quality, $processedCount, $skippedCount);
                    }
                }
            });
        }

        if ($type === 'all' || $type === 'series') {
            $this->info('Processing series posters and backdrops...');
            Items::where('type', 'series')->chunk(100, function ($seriesItems) use (&$processedCount, &$skippedCount, $force, $quality) {
                foreach ($seriesItems as $series) {
                    // Process Poster
                    if ($series->poster && $series->poster !== 'default_poster.jpg') {
                        $this->processImageField($series, 'poster', ['width' => 405, 'height' => 600], $force, $quality, $processedCount, $skippedCount);
                    }
                    // Process Backdrop
                    if ($series->backdrop && $series->backdrop !== 'default_backdrop.jpg') {
                        $this->processImageField($series, 'backdrop', ['width' => 1000, 'height' => 600], $force, $quality, $processedCount, $skippedCount);
                    }
                }
            });
        }

        if ($type === 'all' || $type === 'platforms') {
            $this->info('Processing platform logos...');
            Platform::chunk(100, function ($platforms) use (&$processedCount, &$skippedCount, $force, $quality) {
                foreach ($platforms as $platform) {
                    if ($platform->logo_image_path) {
                        $this->processImageField($platform, 'logo_image_path', ['width' => 200], $force, $quality, $processedCount, $skippedCount);
                    }
                }
            });
        }

        $this->info(sprintf('Image processing complete. %d processed, %d skipped.', $processedCount, $skippedCount));
        return Command::SUCCESS;
    }

    private function processImageField($model, $field, $dimensions, $force, $quality, &$processedCount, &$skippedCount)
    {
        $originalStorageRelativePath = $model->{$field}; // Path from DB, e.g., 'assets/movies/poster/image.jpg' or 'assets/platforms/logo.png'

        if (empty($originalStorageRelativePath)) {
            $this->warn(ucfirst($field) . " path is empty for model ID: " . $model->id . ", skipping.");
            $skippedCount++;
            return;
        }

        if (!Storage::disk('public')->exists($originalStorageRelativePath)) {
            $this->warn(ucfirst($field) . " not found in public storage, skipping: " . $originalStorageRelativePath . " for model ID: " . $model->id);
            $skippedCount++;
            return;
        }

        $pathInfo = pathinfo($originalStorageRelativePath);
        $filenameWithoutExt = $pathInfo['filename'];
        $directory = $pathInfo['dirname'];

        // Prevent processing if original is already webp, unless --force is used
        if (strtolower($pathInfo['extension']) === 'webp' && !$force) {
            $this->line("Skipped (original is already WebP, no force): " . $originalStorageRelativePath . " for model ID: " . $model->id);
            $skippedCount++;
            return;
        }

        $webpFilename = $filenameWithoutExt . '.webp';
        $webpStorageRelativePath = ($directory === '.' ? '' : rtrim($directory, '/') . '/') . $webpFilename;

        if (!$force && Storage::disk('public')->exists($webpStorageRelativePath)) {
            // If original is not webp, but webp version exists and no --force, skip.
            if (strtolower($pathInfo['extension']) !== 'webp') {
                 $this->line("Skipped (WebP exists, no force): " . $originalStorageRelativePath . " for model ID: " . $model->id);
                 $skippedCount++;
                 return;
            }
            // If original is webp, and target is also webp (same file), and no --force, it means we are trying to re-process an existing webp without force.
            // This case is covered by the first check in this block. However, to be explicit:
            if ($originalStorageRelativePath === $webpStorageRelativePath) { // Original is already webp, target is the same
                 $this->line("Skipped (original is WebP, target is the same, no force): " . $originalStorageRelativePath . " for model ID: " . $model->id);
                 $skippedCount++;
                 return;
            }
        }

        try {
            $imageContent = Storage::disk('public')->get($originalStorageRelativePath);
            $img = Image::make($imageContent);

            if (isset($dimensions['width']) && isset($dimensions['height'])) {
                $img->resize($dimensions['width'], $dimensions['height']);
            } elseif (isset($dimensions['width'])) {
                $img->resize($dimensions['width'], null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize(); // Prevent upsizing if the image is smaller than target width
                });
            }
            // If no dimensions specified, it will just convert to WebP without resizing.

            $img->encode('webp', $quality);
            Storage::disk('public')->put($webpStorageRelativePath, (string) $img);

            $this->info("Processed " . $field . ": " . $originalStorageRelativePath . " -> " . $webpStorageRelativePath . " for model ID: " . $model->id);
            $processedCount++;
        } catch (\Exception $e) {
            $this->error("Failed to process " . $field . " " . $originalStorageRelativePath . " for model ID: " . $model->id . ": " . $e->getMessage());
            $skippedCount++;
        }
    }
}
