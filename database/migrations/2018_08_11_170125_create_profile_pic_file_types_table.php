<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilePicFileTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_pic_file_types', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('file_id')->unsigned()->unique()->index();
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');

            $table->boolean('current');

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
        Schema::dropIfExists('profile_pic_file_types');
    }
}
