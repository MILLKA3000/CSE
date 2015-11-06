<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableGrades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('id_student');
            $table->string('fio');
            $table->integer('group');
            $table->integer('code');
            $table->integer('exam_grade');
            $table->integer('grade');
            $table->unsignedInteger('grade_file_id')->nullable();
            $table->foreign('grade_file_id')->references('id')->on('grades_files')->onDelete('cascade');
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
        Schema::drop('grades');
    }
}
