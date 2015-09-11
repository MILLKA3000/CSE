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
            $table->integer('exam_grade');
            $table->integer('grade');
            $table->unsignedInteger('consulting_grade_id')->nullable();
            $table->foreign('consulting_grade_id')->references('id')->on('consulting_grades')->onDelete('set null');
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedInteger('grade_file_id')->nullable();
            $table->foreign('grade_file_id')->references('id')->on('grades_files')->onDelete('set null');
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
