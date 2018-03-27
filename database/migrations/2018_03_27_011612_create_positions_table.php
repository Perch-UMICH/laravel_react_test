<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('lab_id')->unsigned()->index();
            $table->foreign('lab_id')->references('id')->on('labs')->onDelete('cascade');

            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('time_commitment')->nullable();

            $table->integer('open_slots')->nullable();
            $table->integer('filled_slots')->nullable();
            $table->boolean('open')->nullable();

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
        Schema::dropIfExists('positions');
    }
}
