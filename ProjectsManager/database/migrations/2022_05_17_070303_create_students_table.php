<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
			$table->string('student_name');
			$table->string('student_surname');
			$table->unsignedBigInteger('student_group_id')->nullable()->default(null);
			$table->foreign('student_group_id')->references('id')->on('groups');			
			$table->string('student_group_title')->nullable()->default(null);
			$table->unsignedBigInteger('student_project_id')->nullable()->default(null);
			$table->foreign('student_project_id')->references('id')->on('projects');
			$table->string('student_project_title')->nullable()->default(null);
			$table->unsignedBigInteger('api_student_id');
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
