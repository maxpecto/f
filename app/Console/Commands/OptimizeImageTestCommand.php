<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class OptimizeImageTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:optimize-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test image optimization using Intervention Image';

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
        $originalImageName = '@postaci-pat2014.jpg'; // TEST EDİLECEK GÖRSELİN ADI
        $originalDirectory = 'assets/movies/poster'; // Orijinal görselin public altındaki yolu
        $outputDirectory = 'assets/movies/poster';   // Çıktının public altındaki yolu
        
        $originalImagePath = $originalDirectory . '/' . $originalImageName;
        $outputNameBase = pathinfo($originalImageName, PATHINFO_FILENAME);

        $this->info("Optimizing {$originalImagePath}...");

        try {
            if (!Storage::disk('public')->exists($originalImagePath)) {
                $this->error("Original image not found at: public/{$originalImagePath}");
                return 1;
            }

            $img = Image::make(Storage::disk('public')->path($originalImagePath));

            $img->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $webpOutputPath = $outputDirectory . '/' . $outputNameBase . '-carousel.webp';
            
            // Storage::disk('public')->put($webpOutputPath, (string) $img->encode('webp', 75));
            // encode metodu doğrudan string döndürür, bunu Storage::put ile yazabiliriz.
            $img->save(Storage::disk('public')->path($webpOutputPath), 75, 'webp');

            $this->info("Optimized image saved to: public/{$webpOutputPath}");

            // İsteğe bağlı olarak JPEG versiyonu da oluşturulabilir
            // $jpegOutputPath = $outputDirectory . '/' . $outputNameBase . '-carousel.jpg';
            // $img->save(Storage::disk('public')->path($jpegOutputPath), 75, 'jpg');
            // $this->info("Optimized JPEG image saved to: public/{$jpegOutputPath}");

        } catch (\Exception $e) {
            Log::error('Image optimization test error: ' . $e->getMessage());
            $this->error("An error occurred: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
