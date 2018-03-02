<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentTagPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_tag', function (Blueprint $table) {
            $table->integer('student_id')->unsigned()->index();
            $table->integer('tag_id')->unsigned()->index();
            $table->primary(['student_id', 'tag_id']);
        });
        Schema::table('student_tag', function(Blueprint $table)
        {
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('student_tag');
    }
}
