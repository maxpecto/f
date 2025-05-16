<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name');
            $table->string('site_title');
            $table->longText('site_description');
            $table->string('site_keywords')->nullable();

            $table->string('site_logo');
            $table->string('site_favicon');
            $table->string('site_items_per_page');
            $table->string('site_licence_key')->nullable();
            $table->boolean('site_comments_moderation')->default(1);
            $table->string('site_style');
            $table->string('site_player');

            $table->string('site_author')->nullable();
            $table->string('site_email')->nullable();

            $table->string('site_copyright')->nullable();

            $table->string('site_twitter')->nullable();
            $table->string('site_youtube')->nullable();
            $table->string('site_pinterest')->nullable();
            $table->string('site_linkedin')->nullable();
            $table->string('site_facebook')->nullable();

            $table->boolean('maintenance')->default(0);
            $table->longText('site_maintenance_description')->nullable();

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
        Schema::dropIfExists('settings');
    }
}
