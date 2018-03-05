<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabStudentPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_student', function (Blueprint $table) {
            $table->integer('lab_id')->unsigned()->index();
            $table->integer('student_id')->unsigned()->index();
            $table->primary(['lab_id', 'student_id']);
        });
        Schema::table('lab_student', function (Blueprint $table) {
            $table->foreign('lab_id')->references('id')->on('labs')->onDelete('cascade');
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
        Schema::drop('lab_student');
    }
}
