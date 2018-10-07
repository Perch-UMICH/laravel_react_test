<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassExperienceEduExperiencePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_experience_edu_experience', function (Blueprint $table) {
            $table->integer('class_experience_id')->unsigned()->index();
            $table->integer('edu_experience_id')->unsigned()->index();
            $table->primary(['class_experience_id', 'edu_experience_id'], 'class_exp_edu_exp_id');
        });
        Schema::table('class_experience_edu_experience', function (Blueprint $table) {
            $table->foreign('class_experience_id')->references('id')->on('class_experiences')->onDelete('cascade');
            $table->foreign('edu_experience_id')->references('id')->on('edu_experiences')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('class_experience_edu_experience');
    }
}
