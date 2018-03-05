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
            $table->integer('skill_id')->unsigned()->index();
            $table->primary(['lab_id', 'skill_id']);
        });
        Schema::table('lab_skill', function(Blueprint $table)
        {
            $table->foreign('lab_id')->references('id')->on('labs')->onDelete('cascade');
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('cascade');
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
