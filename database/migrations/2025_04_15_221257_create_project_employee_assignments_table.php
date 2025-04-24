<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectEmployeeAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_employee_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Employee ID
            $table->string('required_skill'); // The specific skill required for this assignment
            $table->enum('proficiency_level', ['Beginner', 'Intermediate', 'Advanced', 'Expert'])->nullable(); // Optional: Specific proficiency needed
            $table->integer('years_of_experience_needed')->nullable(); // Optional: Minimum experience needed
            $table->enum('assignment_status', ['Pending HR Approval', 'Approved', 'Rejected', 'Active', 'Completed', 'Removed'])->default('Pending HR Approval');
            $table->timestamps();

            $table->unique(['project_id', 'user_id', 'required_skill'], 'unique_employee_skill_in_project'); // Prevent duplicate assignments for the same skill
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_employee_assignments');
    }
}