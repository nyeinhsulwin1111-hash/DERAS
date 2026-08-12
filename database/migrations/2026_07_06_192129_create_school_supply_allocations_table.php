<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_supply_allocations', function (Blueprint $table) {

            $table->id();

            $table->foreignId('academic_year_id')
                ->constrained('academic_years')
                ->cascadeOnDelete();

            $table->foreignId('grade_id')
                ->constrained('grades')
                ->cascadeOnDelete();

            $table->foreignId('township_id')
                ->nullable()
                ->constrained('townships')
                ->nullOnDelete();

            $table->foreignId('school_supply_item_id')
                ->constrained('school_supply_items')
                ->cascadeOnDelete();


            $table->string('region')->nullable();

            $table->enum('row_type', [
                'township',
                'total',
                'box',
                'loose'
            ])->default('township');

            $table->string('row_label')->nullable();

            $table->integer('school_count')->default(0);

            $table->integer('quantity')->default(0);

            $table->string('remark')->nullable();


            $table->timestamps();


            // FIXED SHORT INDEX NAME
            $table->index(
                [
                    'academic_year_id',
                    'grade_id',
                    'township_id'
                ],
                'ssa_filter_idx'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_supply_allocations');
    }
};
