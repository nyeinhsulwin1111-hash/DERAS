<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('township_id')->constrained('townships')->cascadeOnDelete();
            $table->foreignId('grade_id')->constrained('grades')->cascadeOnDelete();
            $table->foreignId('book_name_id')->constrained('book_names')->cascadeOnDelete();

            $table->integer('previous_balance')->default(0);
            $table->integer('transferred')->default(0);
            $table->integer('enrolled_need')->default(0);
            $table->integer('required_qty')->default(0);
            $table->string('remark')->nullable();

            $table->timestamps();

            $table->unique([
                'academic_year_id',
                'township_id',
                'grade_id',
                'book_name_id',
            ], 'stock_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
