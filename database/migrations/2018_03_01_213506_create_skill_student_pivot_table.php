<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkillStudentPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skill_student', function (Blueprint $table) {
            $table->integer('skill_id')->unsigned()->index();

            $table->integer('student_id')->unsigned()->index();

            $table->primary(['skill_id', 'student_id']);
        });
        Schema::table('skill_student', function(Blueprint $table)
        {
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('skill_student');
    }
}
