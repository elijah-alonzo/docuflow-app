<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_type_id')->constrained('document_types')->restrictOnDelete();
            $table->foreignId('workflow_id')->constrained('workflows')->restrictOnDelete();
            $table->string('title');
            $table->string('file_path');
            $table->foreignId('submitted_by')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->foreignId('current_step_id')->nullable()->constrained('workflow_steps')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
