<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('tmdb_id')->nullable();
            $table->string('imdb_id')->nullable();
            $table->string('title');
            $table->string('slug');

            $table->string('type');

            $table->string('tagline')->nullable();
            $table->longText('overviews')->nullable();
            $table->string('poster')->default('default_poster.jpg');
            $table->string('backdrop')->default('default_backdrop.jpg');

            $table->integer('duration')->nullable();
            $table->integer('rating')->nullable();
            $table->string('release_date')->nullable();
            $table->string('trailer')->nullable();

            $table->integer('views')->default(0);

            $table->longText('player')->nullable();
            $table->longText('download')->nullable();

            $table->boolean('visible')->default(1);
            $table->boolean('feature')->default(0);
            $table->boolean('recommended')->default(0);

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
        Schema::dropIfExists('items');
    }
}
