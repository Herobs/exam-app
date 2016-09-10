<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            // answer id
            $table->increments('id');
            // student id
            $table->integer('student')->unsigned();
            // question id
            $table->integer('question')->unsigned();
            // question type
            $table->enum('type', [
                'true-false',
                'multi-choice',
                'blank-fill',
                'short-answer',
                'general',
                'program-blank-fill',
                'program',
                'database-blank-fill',
                'database',
            ]);
            // submit time
            $table->dateTime('submit');
            // score of this answer
            $table->tinyInteger('score')->unsigned()->nullable();
            // create an index with student and question
            $table->index(['student', 'question']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('answers');
    }
}
