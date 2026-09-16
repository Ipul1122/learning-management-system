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
        Schema::create('graduation_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('class_enrollments')->cascadeOnDelete()->unique();
            $table->foreignId('trainer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('total_jp_earned', 4, 1)->default(20.0);
            $table->decimal('avg_quiz_score', 5, 2)->default(0.00);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('trainer_feedback')->nullable(); // Wajib jika rejected
            $table->timestamp('reviewed_at')->nullable();
            $table->string('certificate_number', 100)->nullable()->unique();
            $table->string('certificate_path', 255)->nullable();
            $table->string('qr_verification_code', 255)->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('certificate_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graduation_submissions');
    }
};
