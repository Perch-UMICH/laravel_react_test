<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResumeFileTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resume_file_types', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned()->unique()->index();
            $table->integer('file_id')->unsigned()->unique()->index();
            $table->boolean('current');

            $table->timestamps();
        });

        Schema::table('resume_file_types', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resume_file_types');
    }
}
