<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolCourseStudentPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schoolcourse_student', function (Blueprint $table) {
            $table->integer('schoolcourse_id')->unsigned()->index();
            $table->integer('student_id')->unsigned()->index();
            $table->primary(['schoolcourse_id', 'student_id']);
        });

        Schema::table('schoolcourse_student', function (Blueprint $table) {
            $table->foreign('schoolcourse_id')->references('id')->on('school_courses')->onDelete('cascade');
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
        Schema::drop('schoolcourse_student');
    }
}
