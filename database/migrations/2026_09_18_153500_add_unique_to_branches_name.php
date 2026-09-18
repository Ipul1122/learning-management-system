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
        $indexes = Schema::getIndexes('branches');
        $hasNameUnique = collect($indexes)->contains(fn ($idx) => in_array('name', $idx['columns'] ?? []) && ($idx['unique'] ?? false));

        if (! $hasNameUnique) {
            Schema::table('branches', function (Blueprint $table) {
                $table->unique('name', 'branches_name_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropUnique('branches_name_unique');
        });
    }
};
