<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSearchenginesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('searchengines', function (Blueprint $table) {
            $table->id();
            $table->string('site_google_verification_code')->nullable();
            $table->string('site_bing_verification_code')->nullable();
            $table->string('site_yandex_verification_code')->nullable();
            $table->longText('site_google_analytics')->nullable();
            $table->string('site_robots')->nullable();
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
        Schema::dropIfExists('searchengines');
    }
}
