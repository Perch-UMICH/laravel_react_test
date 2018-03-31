<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_questions', function (Blueprint $table) {
            $table->increments('id');

            // which application?
            $table->integer('application_id')->unsigned()->index();
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');

            // Question
            $table->string('question');

            // type of question, ref question types
            $table->integer('type')->unsigned()->index();
            $table->foreign('type')->references('id')->on('application_question_types')->onDelete('cascade');

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
        Schema::dropIfExists('application_questions');
    }
}
