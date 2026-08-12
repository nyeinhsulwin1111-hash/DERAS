<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_counts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('grade_id')->constrained('grades')->cascadeOnDelete();
            $table->foreignId('township_id')->nullable()->constrained('townships')->nullOnDelete();
            $table->unsignedInteger('school_count')->default(0);
            $table->timestamps();

            $table->unique(
                ['academic_year_id', 'grade_id', 'township_id'],
                'school_counts_year_grade_township_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_counts');
    }
};
