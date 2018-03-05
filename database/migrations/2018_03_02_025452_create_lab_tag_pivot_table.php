<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabTagPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_tag', function (Blueprint $table) {
            $table->integer('lab_id')->unsigned()->index();
            $table->integer('tag_id')->unsigned()->index();
            $table->primary(['lab_id', 'tag_id']);
        });

        Schema::table('lab_tag', function (Blueprint $table) {
            $table->foreign('lab_id')->references('id')->on('labs')->onDelete('cascade');
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
        Schema::drop('lab_tag');
    }
}
