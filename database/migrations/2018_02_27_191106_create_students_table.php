<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned()->unique()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('year')->nullable();

            $table->text('bio')->nullable();
//            $table->string('major')->nullable(); // normalize
//            $table->double('gpa')->nullable();
//            $table->text('experiences')->nullable();
//            $table->string('classes')->nullable(); // normalize

            $table->string('linkedin_link')->nullable();
            $table->string('website_link')->nullable();

            $table->string('profilepic_path')->nullable();
            $table->string('resume_path')->nullable();

            $table->boolean('is_urop_student')->nullable();

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
        Schema::dropIfExists('students');
    }
}
