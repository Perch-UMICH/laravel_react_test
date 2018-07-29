<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUropPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('urop_positions', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('position_id')->unsigned()->index()->nullable();
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');

            $table->integer('proj_id')->nullable();
            $table->integer('proj_num')->nullable();
            $table->integer('term')->nullable();

            $table->string('proj_title')->nullable();
            $table->string('hrs_per_week')->nullable();
            $table->text('desc')->nullable();
            $table->text('duties')->nullable();
            $table->text('min_qual')->nullable();
            $table->text('learning_comp')->nullable();
            $table->text('training')->nullable();
            $table->string('resume_timing')->nullable();
            $table->string('dept')->nullable();
            $table->string('school')->nullable();

            $table->string('email')->nullable();
            $table->string('phone_num')->nullable();

            $table->string('addr')->nullable();
            $table->string('location')->nullable();

            $table->string('classification')->nullable();
            $table->string('sub_category')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('urop_positions');
    }
}
