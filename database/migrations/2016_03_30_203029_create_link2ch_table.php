<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLink2chTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link2ch', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url');
            $table->string('threadNo');
            $table->string('text');
            $table->dateTime('postedDate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
