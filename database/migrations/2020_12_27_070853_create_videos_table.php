<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('image', 255);
            $table->string('stream_url', 2000)->nullable();
            $table->string('source_id', 255);
            $table->date('crawl_at')->nullable();
            $table->boolean('is_hot')->default(0);
            $table->boolean('is_18')->default(0);
            $table->boolean('status')->default(0);
            $table->timestamps();
            $table->unique('source_id', 'source_id_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
