<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePage2chesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page2ches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('link2ches_id')->unsigned();
            $table->dateTime('postedTime')->default('1000-01-01 00:00:00');
            $table->integer('postedNo');
            $table->string('posterId');
            $table->string('posterName');
            $table->text('text');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->foreign('link2ches_id')->references('id')->on('link2ches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('page2ches');
    }
}
