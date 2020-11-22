<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYoutubeChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('youtube_channels', function (Blueprint $table) {
            $table->id();
            $table->string('youtube_id', 100);
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->datetime('published_at')->nullable();
            $table->string('image', 255)->nullable();
            $table->integer('total_video')->default(0);
            $table->bigInteger('total_view')->default(0);
            $table->integer('total_subscriber')->default(0);
            $table->integer('total_comment')->default(0);
            $table->boolean('is_hidden_subscriber')->default(0);
            $table->date('crawl_at')->nullable();
            $table->integer('cate_id')->nullable();
            $table->integer('master_source_id')->nullable();
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
        Schema::dropIfExists('youtube_channels');
    }
}
