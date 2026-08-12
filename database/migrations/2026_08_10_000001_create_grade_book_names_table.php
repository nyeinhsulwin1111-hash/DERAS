<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grade_book_names', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained('grades')->cascadeOnDelete();
            $table->foreignId('book_name_id')->constrained('book_names')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['grade_id', 'book_name_id'], 'grade_book_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_book_names');
    }
};
