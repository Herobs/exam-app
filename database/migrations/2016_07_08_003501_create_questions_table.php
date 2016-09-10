<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            // question id
            $table->increments('id');
            // the user this question belong to
            // 0 for public question
            $table->integer('user')->unsigned();
            // the exam this question belong to
            $table->integer('exam')->unsigned();
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
            // question description
            $table->text('description');
            // the score of the question(0 - 255)
            $table->tinyInteger('score')->unsigned();
            // the source of the question
            $table->smallInteger('source')->unsigned();
            // reference(0 stand this is a new question)
            $table->integer('ref')->unsigned();
            // create an index with exam and type
            $table->index(['exam', 'type']);
            // create an index with user and type
            $table->index(['user', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('questions');
    }
}
