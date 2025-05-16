<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('series_id')->unsigned();
            $table->integer('season_id');
            $table->integer('episode_id');
            $table->integer('episode_unique_id');
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->string('backdrop')->nullable();
            $table->string('air_date')->nullable();
            $table->longText('player')->nullable();
            $table->longText('download')->nullable();
            $table->string('views');
            $table->timestamps();

            $table->foreign('series_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('episodes');
    }
}
