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
        Schema::create('supply_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('township_id')->constrained('townships')->cascadeOnDelete();
            $table->foreignId('grade_id')->constrained('grades')->cascadeOnDelete();
            $table->foreignId('supply_item_id')->constrained('supply_items')->cascadeOnDelete();

            $table->integer('sequence_no')->default(0);
            $table->integer('unit')->default(0);
            $table->integer('issued_total')->default(0);
            $table->integer('package_count')->default(0);
            $table->integer('loose_count')->default(0);
            $table->string('remark')->nullable();

            $table->timestamps();

            $table->unique([
                'academic_year_id',
                'township_id',
                'grade_id',
                'supply_item_id',
            ], 'supply_detail_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_details');
    }
};
