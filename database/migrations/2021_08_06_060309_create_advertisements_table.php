<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->boolean('activate')->default(1);
            $table->longText('site_728x90_banner')->nullable();
            $table->longText('site_468x60_banner')->nullable();
            $table->longText('site_300x250_banner')->nullable();
            $table->longText('site_320x100_banner')->nullable();
            $table->longText('site_vast_url')->nullable();
            $table->longText('site_popunder')->nullable();
            $table->longText('site_sticky_banner')->nullable();
            $table->longText('site_push_notifications')->nullable();
            $table->longText('site_desktop_fullpage_interstitial')->nullable();
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
        Schema::dropIfExists('advertisements');
    }
}
