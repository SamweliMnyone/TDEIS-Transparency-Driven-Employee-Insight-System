<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('objective');
            $table->text('scope');
            $table->string('estimated_time')->nullable();
            $table->decimal('estimated_cost')->nullable();
            $table->foreignId('project_manager_id')->constrained('users')->onDelete('cascade'); // Assuming a 'users' table and you want to link the PM
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};