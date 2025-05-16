<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->longText('site_left_sidebar')->nullable()->after('site_desktop_fullpage_interstitial');
            $table->longText('site_right_sidebar')->nullable()->after('site_left_sidebar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropColumn(['site_left_sidebar', 'site_right_sidebar']);
        });
    }
};
