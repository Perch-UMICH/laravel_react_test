<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabPicFileTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_pic_file_types', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('file_id')->unsigned()->unique()->index();
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');

            $table->integer('lab_id')->unsigned()->unique()->index();
            $table->foreign('lab_id')->references('id')->on('labs')->onDelete('cascade');

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
        Schema::dropIfExists('lab_pic_file_types');
    }
}
