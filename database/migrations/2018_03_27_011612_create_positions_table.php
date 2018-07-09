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

            $table->integer('lab_id')->unsigned()->index()->nullable();
            $table->foreign('lab_id')->references('id')->on('labs')->onDelete('cascade');

            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('duties')->nullable();
            $table->text('min_qual')->nullable();
            $table->integer('min_time_commitment')->nullable();
            $table->boolean('filled')->nullable();

            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('location')->nullable();

            $table->boolean('is_urop_project')->nullable();

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
