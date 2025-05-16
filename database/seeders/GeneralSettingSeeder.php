<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Settings;
use DB;
use Carbon\Carbon;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'site_name' => 'PixelStream',
                'site_title' => 'Movies & Series Streaming Laravel PHP Script',
                'site_description' => 'PixelStream - Movies & Series Streaming Laravel PHP Script is a complete & outstanding movie streaming solution build on a powerful laravel 8 framework. It auto fetches data from TMDB with API.',
                'site_keywords' => 'PixelStream, pixel stream, movies, series, tv shows, episodes, play, stream, netflix, amazon prime, php, laravel, script',
                'site_logo' => 'logo.png',
                'site_favicon' => 'favicon.png',
                'site_items_per_page' => '25',
                'site_licence_key' => '',
                'site_comments_moderation' => 0,
                'site_style' => 'dark',
                'site_player' => 'trailer',
                'site_author' => 'Nitin Pujari',
                'site_email' => 'pujarinitin92@gmail.com',
                'site_copyright' => '© 2021 - PixelStream - All rights reserved',
                'site_twitter' => 'twitter',
                'site_youtube' => 'youtube',
                'site_pinterest' => 'pinterest',
                'site_linkedin' => 'linkedin',
                'site_facebook' => 'facebook',
                'maintenance' => 0,
                'site_maintenance_description' => 'Sorry for the inconvenience but we’re performing some maintenance at the moment. If you need to you can always contact us, otherwise we’ll be back online shortly!',
                'created_at' => Carbon::now(),
            ]
        ]);
    }
}
