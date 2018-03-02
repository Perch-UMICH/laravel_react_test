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
            $table->foreign('lab_id')->references('id')->on('lab')->onDelete('cascade');
            $table->integer('student_id')->unsigned()->index();
            $table->foreign('student_id')->references('id')->on('student')->onDelete('cascade');
            $table->primary(['lab_id', 'student_id']);
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
