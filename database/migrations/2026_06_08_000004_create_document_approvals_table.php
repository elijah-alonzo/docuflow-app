<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_submission_id')->constrained('document_submissions')->cascadeOnDelete();
            $table->foreignId('document_process_stage_id')->nullable()->constrained('document_process_stages')->nullOnDelete();
            $table->unsignedInteger('cycle')->default(1);
            $table->foreignId('approved_by')->constrained('users')->cascadeOnDelete();
            $table->string('status');
            $table->text('remarks')->nullable();
            $table->timestamp('acted_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_approvals');
    }
};