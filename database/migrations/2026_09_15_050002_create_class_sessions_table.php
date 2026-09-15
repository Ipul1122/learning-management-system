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
        Schema::create('class_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->unsignedInteger('session_order');
            $table->string('title');
            $table->unsignedInteger('jp_duration')->default(2);
            $table->unsignedInteger('minute_duration')->default(90); // jp_duration * 45 menit
            $table->dateTime('session_date');
            $table->text('zoom_url')->nullable();
            $table->string('zoom_meeting_id', 100)->nullable();
            $table->string('zoom_passcode', 100)->nullable();
            $table->foreignId('created_by_user_id')->constrained('users')->restrictOnDelete();
            $table->timestamps();

            $table->index(['class_id', 'session_order']);
            $table->index('session_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_sessions');
    }
};
