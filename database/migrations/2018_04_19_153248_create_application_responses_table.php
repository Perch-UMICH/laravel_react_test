<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_responses', function (Blueprint $table) {
            $table->increments('id');

            // Belongs to a student
            $table->integer('student_id')->unsigned()->index()->nullable();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

            // For an application
            $table->integer('application_id')->unsigned()->index()->nullable();
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');

            $table->boolean('sent');

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
        Schema::dropIfExists('application_responses');
    }
}
