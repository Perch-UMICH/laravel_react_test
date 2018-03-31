<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('applications')){
            Schema::create('applications', function (Blueprint $table) {
                $table->increments('id');

                // which student does this belong to
                $table->integer('student_id')->unsigned()->index();
                //$table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

                // which lab does this belong to
                $table->integer('lab_id')->unsigned()->index();
                //$table->foreign('lab_id')->references('id')->on('labs')->onDelete('cascade');

                $table->integer('status')->unsigned()->index();
                //$table->foreign('status')->references('id')->on('application_statuses')->onDelete('cascade');

                $table->timestamps();
            });
        }
        Schema::table('applications', function(Blueprint $table)
        {
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('lab_id')->references('id')->on('labs')->onDelete('cascade');
            $table->foreign('status')->references('id')->on('application_statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
