<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_experiences', function (Blueprint $table) {
            $table->increments('id');

//            $table->integer('department_id')->unsigned()->index()->nullable();
//            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');

            $table->string('name')->nullable();

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
        Schema::dropIfExists('class_experiences');
    }
}
