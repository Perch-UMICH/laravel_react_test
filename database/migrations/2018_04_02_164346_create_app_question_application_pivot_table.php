<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppQuestionApplicationPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_question_application', function (Blueprint $table) {
            $table->integer('application_id')->unsigned()->index();
            $table->integer('app_question_id')->unsigned()->index();
            $table->primary(['application_id', 'app_question_id']);
        });

        Schema::table('app_question_application', function (Blueprint $table) {
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->foreign('app_question_id')->references('id')->on('app_questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('app_question_application');
    }
}
