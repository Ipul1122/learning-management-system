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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('creator_id')->constrained('users')->restrictOnDelete();
            $table->longText('question_text');
            $table->enum('question_type', ['multiple_choice', 'true_false', 'essay'])->default('multiple_choice');
            $table->unsignedInteger('score_weight')->default(1); // Aturan bobot setara/sama rata
            $table->text('explanation')->nullable(); // Kunci pembahasan
            $table->timestamps();

            $table->index(['branch_id', 'question_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
