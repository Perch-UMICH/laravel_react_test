<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbackUserPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback_user', function (Blueprint $table) {
            $table->integer('feedback_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->primary(['feedback_id', 'user_id']);
        });

        Schema::table('feedback_user', function (Blueprint $table) {
            $table->foreign('feedback_id')->references('id')->on('feedback')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('feedback_user');
    }
}
