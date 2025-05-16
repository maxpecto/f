<?php

namespace App\Http\Composers;

use Illuminate\View\View;
use App\Models\Settings;
use App\Models\Searchengines;
use App\Models\Advertisements;
use Illuminate\Http\Request;

use Cookies;

class SettingsComposer{

	private $request;

    public function __construct(Request $request)
    {
       $this->request = $request;
    }

    public function compose(View $view)
    {
    	$general = Settings::findOrFail('1');
        $seosettings = Searchengines::findOrFail('1');
        $ads = Advertisements::findOrFail('1');
        $view->with('general', $general);
        $view->with('seosettings', $seosettings);
        $view->with('ads', $ads);
    }
}
