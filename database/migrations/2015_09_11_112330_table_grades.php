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
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->bigInteger('id_student');
            $table->integer('exam_grade');
            $table->integer('grade');
            $table->integer('consulting_grade_id')->references('id')->on('consulting_grades')->onDelete('set null');
            $table->integer('user_id')->references('id')->on('user')->onDelete('set null');
            $table->integer('grade_file_id')->references('id')->on('grades_files')->onDelete('set null');
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
