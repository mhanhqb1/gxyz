<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToMasterSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_sources', function (Blueprint $table) {
            $table->boolean('status')->default(0);
            $table->string('loop', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_sources', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('loop');
        });
    }
}
