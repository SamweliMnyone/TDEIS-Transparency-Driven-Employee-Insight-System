<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContributionsTable extends Migration
{
    public function up()
    {
        Schema::create('contributions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['certificate', 'project']);
            $table->text('description')->nullable();
            $table->date('date');
            $table->string('file_path')->nullable(); // To store file path
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Link to user
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contributions');
    }
}