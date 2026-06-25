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
            $table->foreignId('document_workflow_id')->constrained('document_workflows')->restrictOnDelete();
            $table->string('title');
            $table->string('file_path')->nullable();
            $table->foreignId('submitted_by')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->foreignId('current_step_id')->nullable()->constrained('document_workflow_steps')->nullOnDelete();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_submissions');
    }
};
