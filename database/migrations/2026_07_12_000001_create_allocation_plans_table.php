<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('allocation_plans', function (Blueprint $table) {

            $table->id();

            $table->foreignId('academic_year_id')
                ->constrained('academic_years')
                ->cascadeOnDelete();

            $table->foreignId('grade_id')
                ->constrained('grades')
                ->cascadeOnDelete();

            $table->foreignId('book_name_id')
                ->constrained('book_names')
                ->cascadeOnDelete();


            $table->integer('sequence_no');

            // လက်ခံရရှိမှု
            $table->integer('received_books')
                ->default(0);

            // တစ်အိတ်ပါ Unit
            $table->integer('books_per_package')
                ->default(0);

            // အချိုး
            $table->decimal('ratio', 12, 4)
                ->default(0);

            // Summary columns
            $table->integer('eligible_students_total')
                ->default(0);

            $table->integer('allocated_books_total')
                ->default(0);

            $table->integer('student_count_total')
                ->default(0);

            $table->integer('transferable_books_total')
                ->default(0);

            $table->integer('available_total')
                ->default(0);

            $table->integer('surplus_shortage_total')
                ->default(0);

            $table->text('remark')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'academic_year_id',
                'sequence_no'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allocation_plans');
    }
};
