<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('previous_year_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('township_id')->constrained('townships')->cascadeOnDelete();
            $table->foreignId('grade_id')->constrained('grades')->cascadeOnDelete();
            $table->foreignId('book_name_id')->constrained('book_names')->cascadeOnDelete();
            $table->integer('balance')->default(0);
            $table->timestamps();

            $table->unique(
                ['academic_year_id', 'township_id', 'grade_id', 'book_name_id'],
                'pyb_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('previous_year_balances');
    }
};
