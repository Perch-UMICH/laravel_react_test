<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacultyLabPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculty_lab', function (Blueprint $table) {
            $table->integer('faculty_id')->unsigned()->index();
            $table->foreign('faculty_id')->references('id')->on('faculty')->onDelete('cascade');
            $table->integer('lab_id')->unsigned()->index();
            $table->foreign('lab_id')->references('id')->on('lab')->onDelete('cascade');
            $table->primary(['faculty_id', 'lab_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('faculty_lab');
    }
}
