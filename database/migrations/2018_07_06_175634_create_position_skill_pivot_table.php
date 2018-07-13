<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePositionSkillPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('position_skill', function (Blueprint $table) {
            $table->integer('position_id')->unsigned()->index();
            $table->integer('skill_id')->unsigned()->index();
            $table->primary(['position_id', 'skill_id']);
        });
        Schema::table('position_skill', function(Blueprint $table)
        {
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
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
        Schema::drop('position_skill');
    }
}
