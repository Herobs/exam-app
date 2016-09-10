<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            // interval unique integer id
            $table->increments('id');
            // name displayed in the application
            $table->char('name', 16);
            // email address for login and password reset
            $table->char('email', 32)->unique();
            // hashed password
            $table->char('password', 128);
            // student id
            $table->char('student', 16);
            // student major
            $table->char('major', 32);
            // remember token
            $table->rememberToken();
            // time stamps, create_at and update_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
