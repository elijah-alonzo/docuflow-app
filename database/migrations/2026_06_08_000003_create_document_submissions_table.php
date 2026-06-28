<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_category_id')->constrained('document_categories')->restrictOnDelete();
            $table->foreignId('document_process_id')->constrained('document_processes')->restrictOnDelete();
            $table->string('file_path')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('Pending');
            $table->foreignId('current_process_step_id')->nullable()->constrained('document_process_steps')->nullOnDelete();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('document_submission_uploaders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_submission_id')->constrained('document_submissions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['document_submission_id', 'user_id'], 'doc_submission_uploaders_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_submission_uploaders');
        Schema::dropIfExists('document_submissions');
    }
};