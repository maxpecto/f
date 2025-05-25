<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Schema;
use App\Models\Announcement;
use App\Models\Settings;
use App\Models\Searchengines;
use Carbon\Carbon;
use App\Models\Platform;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        // Using class Settings
        View::composer(
            '*', 'App\Http\Composers\SettingsComposer'
        );
        // // Using class Slider
        // View::composer(
        //     '*', 'App\Http\Composers\SlidersComposer'
        // );
        // Using class Movie And Tv Show
        View::composer(
            '*', 'App\Http\Composers\ItemsComposer'
        );

        // General Settings
        View::composer('*', function($view){
            $general = Cache::rememberForever('general_settings', function () {
                return Settings::find(1);
            });
            $view->with('general', $general);
        });

        // Seo Settings
        View::composer('*', function($view){
            $seoSettings = Cache::rememberForever('seo_settings', function () {
                return Searchengines::find(1);
            });
            $view->with('seosettings', $seoSettings);
        });

        // Active Announcements
        View::composer('*', function ($view) {
            $activeAnnouncements = Cache::remember('active_announcements', now()->addMinutes(10), function () {
                $now = Carbon::now();
                return Announcement::where('is_active', true)
                    ->where(function ($query) use ($now) {
                        $query->whereNull('start_date')
                              ->orWhere('start_date', '<=', $now);
                    })
                    ->where(function ($query) use ($now) {
                        $query->whereNull('end_date')
                              ->orWhere('end_date', '>=', $now);
                    })
                    ->orderByDesc('created_at')
                    ->get();
            });
            $view->with('activeAnnouncements', $activeAnnouncements);
        });

        // Tüm frontend view'larına platformları gönder
        View::composer('*', function ($view) {
            if (!isset($view->getData()['platformsGlobal'])) {
                $platforms = Cache::remember('global_platforms', now()->addHours(1), function () {
                    return Platform::whereHas('items', function($query) {
                        $query->where('visible', '1');
                    })->withCount([
                        'items' => function($query) {
                            $query->where('visible', '1');
                        }
                    ])->having('items_count', '>', 0)->orderBy('name')->get();
                });
                $view->with('platformsGlobal', $platforms);
            }
        });
    }
}
