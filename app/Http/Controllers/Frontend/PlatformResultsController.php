<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use App\Models\Items;
use App\Models\Settings; // Genel ayarlar için
use Illuminate\Http\Request;

class PlatformResultsController extends Controller
{
    public function show($platform_slug)
    {
        $general = Settings::findOrFail('1'); // Genel site ayarları (layout için gerekli olabilir)
        $platform = Platform::where('slug', $platform_slug)->firstOrFail();

        $items = Items::where('platform_id', $platform->id)
                        ->where('visible', '1') // Sadece görünür olanlar
                        ->orderBy('created_at', 'desc') // Veya rating vs.
                        ->paginate(20); // Sayfalama

        return view('frontend.platforms.show_items', compact('general', 'platform', 'items'));
    }
}
