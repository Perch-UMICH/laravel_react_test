<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolcourseStudentPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_course_student', function (Blueprint $table) {
            $table->integer('school_course_id')->unsigned()->index();
            $table->integer('student_id')->unsigned()->index();
            $table->primary(['school_course_id', 'student_id']);
        });

        Schema::table('school_course_student', function (Blueprint $table) {
            $table->foreign('school_course_id')->references('id')->on('school_courses')->onDelete('cascade');
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
        Schema::drop('school_course_student');
    }
}
