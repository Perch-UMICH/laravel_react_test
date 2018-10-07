<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEduExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_experiences', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('university_id')->unsigned()->nullable();
            $table->foreign('university_id')->references('id')->on('universities')->onDelete('cascade');

            $table->integer('student_id')->unsigned()->nullable();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            // Pivots with majors and classes

            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();

            $table->boolean('current')->nullable();

            $table->string('year')->nullable();
            $table->string('gpa')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edu_experiences');
    }
}
