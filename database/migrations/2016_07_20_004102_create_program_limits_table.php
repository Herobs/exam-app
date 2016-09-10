<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_limits', function (Blueprint $table) {
            // question id
            $table->integer('id')->unsigned()->index();
            // type(time or memory)
            $table->enum('type', ['time', 'memory']);
            // language
            $table->char('language', 16);
            // limit
            $table->integer('value')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('program_limits');
    }
}
