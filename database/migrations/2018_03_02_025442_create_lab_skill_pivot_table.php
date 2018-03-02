<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabSkillPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_skill', function (Blueprint $table) {
            $table->integer('lab_id')->unsigned()->index();
            $table->foreign('lab_id')->references('id')->on('lab')->onDelete('cascade');
            $table->integer('skill_id')->unsigned()->index();
            $table->foreign('skill_id')->references('id')->on('skill')->onDelete('cascade');
            $table->primary(['lab_id', 'skill_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lab_skill');
    }
}
