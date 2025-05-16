<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Searchengines;
use DB;
use Carbon\Carbon;

class SearchEnginesSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('searchengines')->insert([
            [
            	'site_google_verification_code' => '',
            	'site_bing_verification_code' => '',
            	'site_yandex_verification_code' => '',
            	'site_google_analytics' => '',
            	'site_robots' => 'follow, index',
                'created_at' => Carbon::now(),
            ]
        ]);
    }
}
