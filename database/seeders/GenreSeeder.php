<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genres;
use DB;
use Carbon\Carbon;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genres')->insert([
            [
                'name' => 'Action',
                'id' => 28,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Adventure',
                'id' => 12,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Animation',
                'id' => 16,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Comedy',
                'id' => 35,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Crime',
                'id' => 80,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Documentary',
                'id' => 99,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Drama',
                'id' => 18,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Family',
                'id' => 10751,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Kids',
                'id' => 10762,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Fantasy',
                'id' => 14,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'History',
                'id' => 36,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Horror',
                'id' => 27,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Music',
                'id' => 10402,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Mystery',
                'id' => 9648,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'News',
                'id' => 10763,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Reality',
                'id' => 10764,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Soap',
                'id' => 10766,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Talk',
                'id' => 10767,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'War & Politics',
                'id' => 10768,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Romance',
                'id' => 10749,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Science Fiction',
                'id' => 878,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'TV Movie',
                'id' => 10770,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Thriller',
                'id' => 53,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'War',
                'id' => 10752,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Western',
                'id' => 37,
                'visible' => 1,
                'created_at' => Carbon::now(),
            ]
        ]);
    }
}
