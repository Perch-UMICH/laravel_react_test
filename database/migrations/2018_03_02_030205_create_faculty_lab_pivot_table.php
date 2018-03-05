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
            $table->integer('lab_id')->unsigned()->index();
            $table->primary(['faculty_id', 'lab_id']);
        });
        Schema::table('faculty_lab', function (Blueprint $table) {
            $table->foreign('faculty_id')->references('id')->on('faculties')->onDelete('cascade');
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
        Schema::drop('faculty_lab');
    }
}
