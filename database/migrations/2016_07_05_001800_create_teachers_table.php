<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            // teacher id
            $table->increments('id');
            // teacher name
            $table->char('name', 16);
            // email address for login and password reset
            $table->char('email', 32)->unique();
            // hashed password
            $table->char('password', 128);
            // phone number
            $table->char('tel', 16);
            // right
            // all rights see the contants config
            $table->integer('rights')->unsigned()->default(0);
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
        Schema::drop('teachers');
    }
}
