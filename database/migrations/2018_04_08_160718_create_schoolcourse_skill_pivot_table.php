<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolcourseSkillPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_course_skill', function (Blueprint $table) {
            $table->integer('school_course_id')->unsigned()->index();
            $table->integer('skill_id')->unsigned()->index();
            $table->primary(['school_course_id', 'skill_id']);
        });
        Schema::table('school_course_skill', function (Blueprint $table) {
            $table->foreign('school_course_id')->references('id')->on('school_courses')->onDelete('cascade');
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('school_course_skill');
    }
}
