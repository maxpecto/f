<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use DB;
use Carbon\Carbon;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'fname' => 'Admin',
                'lname' => 'Stream',
                'username' => 'admin',
                'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries",
                'email' => 'admin@me.com',
                'password' => bcrypt('password'),
                'role' => 'administrators',
                'profile_img' => '/admin.jpg',
                'blocked' => 0,
                'verify_Badge' => 1,
                'location' => 'India',
                'website' => 'https://geekycoder.in',
                'instagram' => 'https://instagram.com/pujari1992',
                'twitter' => 'https://twitter.com/',
                'created_at' => Carbon::now(),
            ],
            [
                'fname' => 'Member',
                'lname' => 'Stream',
                'username' => 'member',
                'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries",
                'email' => 'admin@me.com',
                'email' => 'member@me.com',
                'password' => bcrypt('password'),
                'role' => 'members',
                'profile_img' => '/member.jpg',
                'blocked' => 0,
                'verify_Badge' => 0,
                'location' => 'India',
                'website' => 'https://geekycoder.in',
                'instagram' => 'https://instagram.com/pujari1992',
                'twitter' => 'https://twitter.com/',
                'created_at' => Carbon::now(),
            ]
        ]);

    }
}
