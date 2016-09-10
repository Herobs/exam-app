<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            // exam id
            $table->increments('id');
            // exam name, use char not varchar
            // becase there is limited exam records
            $table->char('name', 64);
            // exam start time
            $table->dateTime('start')->index();
            // time of exam duration is seconds
            $table->mediumInteger('duration')->unsigned();
            // the exam holder
            $table->integer('holder')->unsigned();
            // the type of the exam(login strategy)
            $table->enum('type', [
                'student',
                'account',
                'password',
            ]);
            // exam public password
            $table->string('password', 16)->nullable();
            // hidden the exam
            $table->boolean('hidden');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('exams');
    }
}
