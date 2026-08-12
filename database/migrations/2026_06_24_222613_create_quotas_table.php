<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('academic_year_id')
                ->constrained('academic_years')
                ->cascadeOnDelete();

            $table->foreignId('township_id')
                ->constrained('townships')
                ->cascadeOnDelete();

            $table->integer('primary_public')->default(0);
            $table->integer('primary_monk')->default(0);
            $table->integer('primary_private')->default(0);
            $table->integer('primary_total')->default(0);

            $table->integer('middle_public')->default(0);
            $table->integer('middle_monk')->default(0);
            $table->integer('middle_private')->default(0);
            $table->integer('middle_total')->default(0);

            $table->integer('high_public')->default(0);
            $table->integer('high_monk')->default(0);
            $table->integer('high_private')->default(0);
            $table->integer('high_total')->default(0);

            $table->integer('grand_public')->default(0);
            $table->integer('grand_monk')->default(0);
            $table->integer('grand_private')->default(0);
            $table->integer('grand_total')->default(0);

            $table->integer('agriculture')->default(0);
            $table->integer('total_with_agriculture')->default(0);
            $table->integer('distribution_total')->default(0);

            $table->timestamps();

            $table->unique(['academic_year_id', 'township_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotas');
    }
};
