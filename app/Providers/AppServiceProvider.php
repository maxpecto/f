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
            $view->with('general', Settings::find(1));
        });

        // Seo Settings
        View::composer('*', function($view){
            $view->with('seosettings', Searchengines::find(1));
        });

        // Active Announcements
        View::composer('*', function ($view) {
            $now = Carbon::now();
            $activeAnnouncements = Announcement::where('is_active', true)
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
            $view->with('activeAnnouncements', $activeAnnouncements);
        });

        // Tüm frontend view'larına platformları gönder
        View::composer('*', function ($view) {
            if (!isset($view->getData()['platformsGlobal'])) {
                $platforms = Platform::whereHas('items', function($query) {
                    $query->where('visible', '1');
                })->withCount([
                    'items' => function($query) {
                        $query->where('visible', '1');
                    }
                ])->having('items_count', '>', 0)->orderBy('name')->get();
                
                $view->with('platformsGlobal', $platforms);
            }
        });
    }
}
