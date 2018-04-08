<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolCourseSkillsPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schoolcourse_skill', function (Blueprint $table) {
            $table->integer('schoolcourse_id')->unsigned()->index();
            $table->integer('skill_id')->unsigned()->index();
            $table->primary(['schoolcourse_id', 'skill_id']);
        });
        Schema::table('schoolcourse_skill', function (Blueprint $table) {
            $table->foreign('schoolcourse_id')->references('id')->on('school_courses')->onDelete('cascade');
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
        Schema::drop('schoolcourse_skill');
    }
}
