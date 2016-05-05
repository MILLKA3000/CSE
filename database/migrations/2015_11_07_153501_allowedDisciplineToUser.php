<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllowedDisciplineToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allowed_discipline', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('departments_id')->nullable();
            $table->foreign('departments_id')->references('id')->on('departments')->onDelete('cascade');
            $table->string('arrayAllowed',5000);
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
        Schema::drop('allowed_discipline');
    }
}
