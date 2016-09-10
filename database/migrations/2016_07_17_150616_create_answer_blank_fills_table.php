<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswerBlankFillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_blank_fill', function (Blueprint $table) {
            // answer id
            $table->integer('id')->unsigned();
            // order
            $table->tinyInteger('order')->unsigned();
            // answer
            $table->string('answer');
            // create an index with student and question
            $table->unique(['id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('answer_blank_fill');
    }
}
