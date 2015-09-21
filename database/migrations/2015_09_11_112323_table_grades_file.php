<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableGradesFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grades_files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('TestListID');
            $table->string('DisciplineID');
            $table->string('DisciplineVariantID');
            $table->string('ModuleVariantID');
            $table->string('NameDiscipline');
            $table->string('NameModule');
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedInteger('file_info_id')->nullable();
            $table->foreign('file_info_id')->references('id')->on('file_info')->onDelete('set null');
            $table->unsignedInteger('type_exam_id')->nullable();
            $table->foreign('type_exam_id')->references('id')->on('type_exam')->onDelete('set null');
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
        Schema::drop('grades_files');
    }
}
