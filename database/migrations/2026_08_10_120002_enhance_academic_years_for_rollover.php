<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('academic_years', function (Blueprint $table) {
            $table->unsignedSmallInteger('start_year')->nullable()->after('name');
            $table->unsignedSmallInteger('end_year')->nullable()->after('start_year');
            $table->boolean('is_current')->default(false)->after('is_active');
            $table->string('status', 20)->default('active')->after('is_current');
        });

        // Backfill from name like 2025-2026
        $years = DB::table('academic_years')->get();
        foreach ($years as $year) {
            if (preg_match('/^(\d{4})\s*-\s*(\d{4})$/', (string) $year->name, $m)) {
                DB::table('academic_years')->where('id', $year->id)->update([
                    'start_year' => (int) $m[1],
                    'end_year' => (int) $m[2],
                ]);
            }
        }

        // Mark 2025-2026 as current if present
        $current = DB::table('academic_years')->where('name', '2025-2026')->first();
        if ($current) {
            DB::table('academic_years')->update(['is_current' => false]);
            DB::table('academic_years')->where('id', $current->id)->update([
                'is_current' => true,
                'status' => 'active',
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('academic_years', function (Blueprint $table) {
            $table->dropColumn(['start_year', 'end_year', 'is_current', 'status']);
        });
    }
};
