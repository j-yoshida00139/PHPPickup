<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLink2chesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link2ches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url')->unique();
            $table->string('text');
            $table->date('postedDate')->default('1000-01-01');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('link2ches');
    }
}
