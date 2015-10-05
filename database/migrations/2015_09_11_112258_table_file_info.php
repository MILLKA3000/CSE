<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableFileInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xls_file_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path');
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('xml_file_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('EduYear');
            $table->string('Semester');
            $table->string('DepartmentId');
            $table->string('SpecialityId');
            $table->string('DisciplineVariantID');
            $table->string('ModuleVariantID');
            $table->string('ModuleNum');
            $table->string('NameDiscipline');
            $table->string('NameModule');
            $table->string('path');
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('xls_file_info');
        Schema::drop('xml_file_info');
    }
}
