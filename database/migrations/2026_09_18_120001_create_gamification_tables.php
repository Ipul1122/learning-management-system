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
        // 1. Tambah kolom total_points dan level di tabel users jika belum ada
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'total_points')) {
                $table->unsignedInteger('total_points')->default(0)->after('status');
            }
            if (! Schema::hasColumn('users', 'level')) {
                $table->unsignedInteger('level')->default(1)->after('total_points');
            }
        });

        // 2. Tabel Master Lencana Prestasi (Badges)
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description');
            $table->string('icon')->default('trophy');
            $table->string('category')->default('general');
            $table->unsignedInteger('xp_reward')->default(50);
            $table->timestamps();
        });

        // 3. Tabel Penghargaan Lencana ke Pengguna (User Badges)
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('badge_id')->constrained('badges')->cascadeOnDelete();
            $table->timestamp('earned_at')->useCurrent();
            $table->unique(['user_id', 'badge_id']);
        });

        // 4. Tabel Riwayat Mutasi Poin XP (Point Transactions)
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('points');
            $table->string('source_type'); // 'enrollment', 'attendance', 'quiz', 'graduation', 'forum', 'badge'
            $table->string('description');
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('badges');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'level')) {
                $table->dropColumn('level');
            }
            if (Schema::hasColumn('users', 'total_points')) {
                $table->dropColumn('total_points');
            }
        });
    }
};
