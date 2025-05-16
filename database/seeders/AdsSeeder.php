<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Settings;
use DB;
use Carbon\Carbon;

class AdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('advertisements')->insert(
            [
                'site_728x90_banner' => 'PGEgaHJlZj0iIyI+PGltZyBzcmM9Ii9zZXR0aW5nX2ltZy9hZHZlcnRpc2VtZW50LzcyOHg5MC1hZC11bml0LmpwZyIgYWx0PSI3Mjh4OTAtYWQtdW5pdCI+PC9hPg==',
                'site_468x60_banner' => 'PGEgaHJlZj0iIyI+PGltZyBzcmM9Ii9zZXR0aW5nX2ltZy9hZHZlcnRpc2VtZW50LzQ2OHg2MC1hZC11bml0LmpwZyIgYWx0PSI0Njh4NjAtYWQtdW5pdCI+PC9hPg==',
                'site_300x250_banner' => 'PGEgaHJlZj0iIyI+PGltZyBzcmM9Ii9zZXR0aW5nX2ltZy9hZHZlcnRpc2VtZW50LzMwMHgyNTAtYWQtdW5pdC5qcGciIGFsdD0iMzAweDI1MC1hZC11bml0Ij48L2E+',
                'site_320x100_banner' => 'PGEgaHJlZj0iIyI+PGltZyBzcmM9Ii9zZXR0aW5nX2ltZy9hZHZlcnRpc2VtZW50LzMyMHgxMDAtYWQtdW5pdC5qcGciIGFsdD0iMzIweDEwMC1hZC11bml0Ij48L2E+',
                'site_vast_url' => 'aHR0cHM6Ly93d3cucmFkaWFudG1lZGlhcGxheWVyLmNvbS92YXN0L3RhZ3MvaW5saW5lLWxpbmVhci54bWw=',
                'site_popunder' => '',
                'site_sticky_banner' => '',
                'site_push_notifications' => '',
                'site_desktop_fullpage_interstitial' => '',
                'created_at' => Carbon::now(),
            ]
        );
    }
}
