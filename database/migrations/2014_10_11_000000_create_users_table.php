<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fname');
            $table->string('lname');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->longText('description')->nullable();
            $table->string('profile_img')->default('/default.png');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('hidden_items')->default(0);
            $table->string('role')->default('members');
            $table->integer('views')->default(0);
            $table->string('website')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('location')->nullable();
            $table->boolean('blocked')->default(0);
            $table->boolean('verify_Badge')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
