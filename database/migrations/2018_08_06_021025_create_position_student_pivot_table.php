<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePositionStudentPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('position_student', function (Blueprint $table) {
            $table->integer('position_id')->unsigned()->index();
            $table->integer('student_id')->unsigned()->index();
            $table->primary(['position_id', 'student_id']);
        });
        Schema::table('position_student', function (Blueprint $table) {
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
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
        Schema::drop('position_student');
    }
}
