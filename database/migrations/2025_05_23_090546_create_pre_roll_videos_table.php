<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreRollVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_roll_videos', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('video_url');
            $table->string('target_url')->nullable();
            $table->integer('skippable_after_seconds')->nullable();
            $table->boolean('is_active')->default(false);
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
        Schema::dropIfExists('pre_roll_videos');
    }
}
