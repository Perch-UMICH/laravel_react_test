<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppQuestionResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_question_responses', function (Blueprint $table) {
            $table->increments('id');

            // Attached to an application response
            $table->integer('application_response_id')->unsigned()->index()->nullable();
            $table->foreign('application_response_id')->references('id')->on('application_responses')->onDelete('cascade');

            $table->text('response')->nullable();
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
    }
}
