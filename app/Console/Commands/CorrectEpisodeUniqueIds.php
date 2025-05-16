<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Episodes;
use Illuminate\Support\Facades\Log;

class CorrectEpisodeUniqueIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'correct:episode-unique-ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corrects the episode_unique_id based on series_id, season_id, and episode_id.';

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
        $this->info('Starting episode unique ID correction...');
        $updatedCount = 0;
        $skippedCount = 0;
        $errorCount = 0;

        Episodes::chunkById(200, function ($episodes) use (&$updatedCount, &$skippedCount, &$errorCount) {
            foreach ($episodes as $episode) {
                try {
                    $correctUniqueIdStr = $episode->series_id . $episode->season_id . $episode->episode_id;
                    
                    if (!ctype_digit($correctUniqueIdStr)) {
                         $this->warn("Skipping Episode ID: {$episode->id} - Combined ID is not purely numeric: {$correctUniqueIdStr}");
                         $errorCount++;
                         continue;
                    }
                    
                    if (strlen($correctUniqueIdStr) > 10 && $correctUniqueIdStr > 2147483647) {
                         $this->warn("Skipping Episode ID: {$episode->id} - Calculated ID may exceed integer limits: {$correctUniqueIdStr}");
                         $errorCount++;
                         continue;
                    }
                    
                    $correctUniqueId = (int)$correctUniqueIdStr;

                    if ($episode->episode_unique_id !== $correctUniqueId) {
                        $oldId = $episode->episode_unique_id;
                        $episode->episode_unique_id = $correctUniqueId;
                        $episode->save();
                        $updatedCount++;
                        $this->line("Updated Episode ID: {$episode->id} (Old: {$oldId}, New: {$correctUniqueId})");
                    } else {
                        $skippedCount++;
                    }
                } catch (\Throwable $e) {
                    $this->error("Error processing Episode ID: {$episode->id} - " . $e->getMessage());
                    Log::error("Error correcting unique ID for Episode {$episode->id}: " . $e->getMessage());
                    $errorCount++;
                }
            }
             $this->info("Processed a chunk of episodes...");
        });

        $this->info('-----------------------------------------');
        $this->info("Correction process finished.");
        $this->info("Updated: {$updatedCount} episodes.");
        $this->info("Skipped (already correct): {$skippedCount} episodes.");
        $this->error("Errors/Warnings: {$errorCount} episodes.");

        if ($errorCount > 0) {
             $this->warn('Please check the logs or console output for details on errors/warnings.');
             $this->warn('Some IDs might exceed integer limits or contain non-numeric parts.');
        }

        return 0;
    }
}
