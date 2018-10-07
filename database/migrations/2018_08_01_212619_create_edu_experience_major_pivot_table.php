<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateeduExperienceMajorPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_experience_major', function (Blueprint $table) {
            $table->integer('edu_experience_id')->unsigned()->index();
            $table->integer('major_id')->unsigned()->index();
            $table->primary(['edu_experience_id', 'major_id'], 'edu_exp_major_id');
        });
        Schema::table('edu_experience_major', function (Blueprint $table) {
            $table->foreign('edu_experience_id')->references('id')->on('edu_experiences')->onDelete('cascade');
            $table->foreign('major_id')->references('id')->on('majors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('edu_experience_major');
    }
}
