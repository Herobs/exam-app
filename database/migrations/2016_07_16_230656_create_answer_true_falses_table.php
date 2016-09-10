<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswerTrueFalsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_true_false', function (Blueprint $table) {
            // answer id
            $table->integer('id')->unsigned()->unique();
            // answer
            $table->enum('answer', ['true', 'false']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('answer_true_false');
    }
}
