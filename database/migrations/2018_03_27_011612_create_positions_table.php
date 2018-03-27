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

            $table->integer('lab_id')->unsigned()->unique()->index();
            $table->foreign('lab_id')->references('id')->on('labs')->onDelete('cascade');

            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('timeCommitment')->nullable();

            $table->integer('openSlots')->nullable();
            $table->integer('filledSlots')->nullable();
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
