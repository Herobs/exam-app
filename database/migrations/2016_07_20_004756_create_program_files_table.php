<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_files', function (Blueprint $table) {
            // question id
            $table->integer('id')->unsigned()->index();
            // case id
            $table->tinyInteger('case')->unsigned();
            // type
            $table->enum('type', ['input', 'output']);
            // content
            $table->mediumText('content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('program_files');
    }
}
