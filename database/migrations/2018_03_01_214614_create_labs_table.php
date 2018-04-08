<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index()->nullable();
            $table->string('department')->index()->nullable();
            $table->text('description')->nullable();

            $table->text('publications')->nullable();
            $table->string('url')->nullable();
            $table->string('location')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();

            // lab qualifications
            $table->float('gpa')->nullable(); // required gpa to apply
            $table->integer('weeklyCommitment')->nullable(); // weekly time commitment
            $table->integer('num_open_spots')->nullable();

            $table->string('labpic_path')->nullable();

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
        Schema::dropIfExists('labs');
    }
}
