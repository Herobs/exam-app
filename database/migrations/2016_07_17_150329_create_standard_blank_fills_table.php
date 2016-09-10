<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStandardBlankFillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('standard_blank_fill', function (Blueprint $table) {
            // question id
            $table->integer('id')->unsigned()->index();
            // option order
            $table->tinyInteger('order')->unsigned();
            // answer
            $table->char('answer', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('standard_blank_fill');
    }
}
