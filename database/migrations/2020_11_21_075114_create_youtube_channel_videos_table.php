<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYoutubeChannelVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('youtube_channel_videos', function (Blueprint $table) {
            $table->id();
            $table->string('youtube_channel_id', 100);
            $table->string('youtube_id', 100);
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->datetime('published_at')->nullable();
            $table->string('image', 255)->nullable();
            $table->integer('total_view')->default(0);
            $table->integer('total_comment')->default(0);
            $table->integer('total_like')->default(0);
            $table->integer('total_dislike')->default(0);
            $table->integer('category_id')->default(0);
            $table->date('crawl_at')->nullable();
            $table->boolean('is_hot')->default(0);
            $table->boolean('is_18')->default(0);
            $table->boolean('status')->default(0);
            $table->timestamps();
            $table->unique('youtube_id', 'youtube_id_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('youtube_channel_videos');
    }
}
