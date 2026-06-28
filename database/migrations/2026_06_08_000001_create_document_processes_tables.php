<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_processes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('document_process_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_process_id')->constrained('document_processes')->cascadeOnDelete();
            $table->integer('stage_order');
            $table->string('stage_name');
            $table->unsignedBigInteger('assigned_role_id');
            $table->string('action_label')->default('Approve');
            $table->string('approve_status');
            $table->string('reject_status');
            $table->timestamps();

            $table->foreign('assigned_role_id')->references('id')->on('roles')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_process_stages');
        Schema::dropIfExists('document_processes');
    }
};