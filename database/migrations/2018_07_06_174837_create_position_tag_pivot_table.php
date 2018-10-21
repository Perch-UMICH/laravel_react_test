<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePositionTagPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('position_tag', function (Blueprint $table) {
            $table->integer('position_id')->unsigned()->index();
            $table->integer('tag_id')->unsigned()->index();
            $table->primary(['position_id', 'tag_id']);
        });
        Schema::table('position_tag', function(Blueprint $table)
        {
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('position_tag');
    }
}
