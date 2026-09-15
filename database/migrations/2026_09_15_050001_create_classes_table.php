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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('trainer_id')->constrained('users')->restrictOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['offline', 'online', 'hybrid']);
            $table->unsignedInteger('offline_capacity')->default(40); // Maks 40 untuk offline / kursi fisik hybrid
            $table->unsignedInteger('online_capacity')->default(500);  // Kapasitas daring
            $table->unsignedInteger('enrolled_offline')->default(0);
            $table->unsignedInteger('enrolled_online')->default(0);
            $table->unsignedInteger('required_jp')->default(20);        // Standar target 20 JP (900 menit)
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['draft', 'open', 'ongoing', 'completed', 'cancelled'])->default('draft');
            $table->timestamps();

            $table->index(['branch_id', 'status']);
            $table->index('trainer_id');
            $table->index('type');
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
