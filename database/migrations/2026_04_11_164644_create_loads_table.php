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
        Schema::create('loads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years');
            $table->enum('term', ['First Semester', 'Second Semester', 'Third Semester', 'Summer Semester']);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('grading_sheet')->nullable();
            $table->enum('grading_sheet_status', ['pending', 'to_verify', 'to_endorse', 'submitted'])
                ->default('pending');
            $table->timestamps();

            $table->unique(['program_id', 'subject_id', 'term', 'user_id', 'academic_year_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loads');
    }
};
