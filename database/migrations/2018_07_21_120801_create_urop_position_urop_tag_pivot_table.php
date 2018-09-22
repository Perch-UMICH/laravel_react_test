<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUropPositionUropTagPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('urop_position_urop_tag', function (Blueprint $table) {
            $table->integer('urop_position_id')->unsigned()->index();
            $table->integer('urop_tag_id')->unsigned()->index();
            $table->primary(['urop_position_id', 'urop_tag_id']);
        });
        Schema::table('urop_position_urop_tag', function(Blueprint $table)
        {
            $table->foreign('urop_position_id')->references('id')->on('urop_positions')->onDelete('cascade');
            $table->foreign('urop_tag_id')->references('id')->on('urop_tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('urop_position_urop_tag');
    }
}
