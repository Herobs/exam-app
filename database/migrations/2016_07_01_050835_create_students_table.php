<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            // global student id
            $table->increments('id');
            // exam id the student belong to
            $table->integer('exam')->unsigned();
            // student id
            $table->char('student', 16);
            // student name
            $table->char('name', 16);
            // major
            $table->char('major', 32)->nullable();
            // import password
            $table->char('password', 128)->nullable();
            // date time last login
            $table->dateTime('last');
            // ip address last login
            $table->ipAddress('ip');
            // create an index with exam and student
            $table->index(['exam', 'student']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('students');
    }
}
