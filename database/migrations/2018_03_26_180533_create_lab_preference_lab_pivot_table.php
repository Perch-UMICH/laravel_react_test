<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabPreferenceLabPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_preference_lab', function (Blueprint $table) {
            $table->integer('lab_preference_id')->unsigned()->index();
            $table->integer('lab_id')->unsigned()->index();
            $table->primary(['lab_preference_id', 'lab_id']);
        });

        Schema::table('lab_preference_lab', function (Blueprint $table) {
            $table->foreign('lab_preference_id')->references('id')->on('lab_preferences')->onDelete('cascade');
            $table->foreign('lab_id')->references('id')->on('labs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lab_preference_lab');
    }
}
