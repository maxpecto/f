<?php

namespace App\Http\Composers;

use Illuminate\View\View;
use App\Models\Settings;
use App\Models\Searchengines;
use App\Models\Advertisements;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsComposer{

	private $request;

    public function __construct(Request $request)
    {
       $this->request = $request;
    }

    public function compose(View $view)
    {
    	$general = Cache::get('general_settings');
        if (!$general) {
            $general = Cache::rememberForever('general_settings', function () {
                return Settings::find(1);
            });
        }

        $seoSettings = Cache::get('seo_settings');
        if (!$seoSettings) {
            $seoSettings = Cache::rememberForever('seo_settings', function () {
                return Searchengines::find(1);
            });
        }

        $ads = Cache::rememberForever('advertisements_settings', function () {
            return Advertisements::find(1);
        });

        $view->with('general', $general);
        $view->with('seosettings', $seoSettings);
        $view->with('ads', $ads);
    }
}
