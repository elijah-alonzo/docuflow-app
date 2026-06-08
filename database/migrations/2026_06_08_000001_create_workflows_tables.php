<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflows', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained('workflows')->cascadeOnDelete();
            $table->integer('step_order');
            $table->string('step_name');
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
        Schema::dropIfExists('workflow_steps');
        Schema::dropIfExists('workflows');
    }
};
