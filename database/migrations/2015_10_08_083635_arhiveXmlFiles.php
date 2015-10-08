<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ArhiveXmlFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arhive_xml_files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_file');
            $table->string('testlistid',20);
            $table->string('modulevariantid',20);
            $table->unsignedInteger('id_info_xml')->nullable();
            $table->foreign('id_info_xml')->references('id')->on('xml_file_info')->onDelete('set null');
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
        Schema::drop('arhive_xml_files');
    }
}
