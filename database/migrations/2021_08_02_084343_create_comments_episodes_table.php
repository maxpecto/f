<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments_episodes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('episodes_id')->unsigned();
            $table->foreign('episodes_id')->references('id')->on('episodes')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('comments_id')->unsigned();
            $table->foreign('comments_id')->references('id')->on('comments')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments_episodes');
    }
}
