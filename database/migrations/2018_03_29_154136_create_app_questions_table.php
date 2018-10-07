<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_questions', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('application_id')->unsigned()->index()->nullable();
            $table->foreign('application_id')->references('id')->on('applications');

            // Question
            $table->string('question');
            $table->integer('number')->nullable();

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
        Schema::dropIfExists('app_question_responses');
        Schema::dropIfExists('app_questions');
    }
}
