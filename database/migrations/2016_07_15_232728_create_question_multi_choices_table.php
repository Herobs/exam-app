<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionMultiChoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_multi_choice', function (Blueprint $table) {
            // question id
            $table->integer('id')->unsigned()->index();
            // option order
            $table->tinyInteger('order')->unsigned();
            // option
            $table->char('option', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('question_multi_choice');
    }
}
