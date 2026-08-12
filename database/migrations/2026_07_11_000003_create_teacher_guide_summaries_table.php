<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_guide_summaries', function (Blueprint $table) {
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

            $table->unsignedInteger('group_no');
            $table->text('group_title');
            $table->string('guide_type');
            $table->unsignedInteger('sequence_no');

            $table->integer('previous_balance')->nullable();
            $table->integer('fiscal_year_quota')->nullable();
            $table->integer('total_books')->nullable();
            $table->integer('distributed_books')->nullable();
            $table->integer('remaining_books')->nullable();
            $table->text('remark')->nullable();

            $table->timestamps();

            $table->unique([
                'academic_year_id',
                'group_no',
                'grade_id',
                'book_name_id',
                'guide_type',
                'sequence_no',
            ], 'teacher_guide_summary_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_guide_summaries');
    }
};
