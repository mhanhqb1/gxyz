<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('type', 50);//image, video, movie
            $table->string('source_type', 50);//facebook, flickr, ...
            $table->text('source_params');//user_id_:_value,param2_:_value2
            $table->date('crawl_at')->nullable();
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
        Schema::dropIfExists('master_sources');
    }
}
