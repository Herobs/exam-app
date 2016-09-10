<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_program', function (Blueprint $table) {
            // question id
            $table->integer('id')->unsigned()->index();
            // remote id
            $table->integer('remote')->unsigned();
            // title
            $table->string('title')->nullable();
            // output limit
            $table->integer('output_limit')->unsigned();
            // special judge
            $table->boolean('special_judge')->default(false);
            // test case
            $table->tinyInteger('test_case');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('question_program');
    }
}
