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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('creator_id')->constrained('users')->restrictOnDelete();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->unsignedInteger('time_limit_minutes')->default(30);
            $table->decimal('passing_grade', 5, 2)->default(75.00);
            $table->boolean('is_randomized')->default(true);
            $table->unsignedInteger('max_attempts')->default(1);
            $table->timestamps();

            $table->index(['class_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
