<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_guides', function (Blueprint $table) {
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

            $table->integer('group_no')->default(0);

            $table->text('group_title');

            $table->string('guide_type');

            $table->integer('sequence_no')->default(0);

            $table->integer('kg_to_g12_quota')->default(0);

            $table->integer('g1_to_g5_quota')->default(0);

            $table->integer('total_quota')->default(0);

            $table->text('remark')->nullable();

            $table->timestamps();

            // KG to G-12 ခွဲတမ်း
            $table->unsignedInteger('kg_g12_myanaung_qty')->nullable();
            $table->unsignedInteger('kg_g12_kyankhin_qty')->nullable();
            $table->unsignedInteger('kg_g12_ingapu_qty')->nullable();

            // G-1 to G-5 ခွဲတမ်း
            $table->unsignedInteger('g1_g5_myanaung_qty')->nullable();
            $table->unsignedInteger('g1_g5_kyankhin_qty')->nullable();
            $table->unsignedInteger('g1_g5_ingapu_qty')->nullable();

            // နှစ်မျိုးပေါင်း ဖြန့်ဝေမှု
            $table->unsignedInteger('total_myanaung_qty')->nullable();
            $table->unsignedInteger('total_kyankhin_qty')->nullable();
            $table->unsignedInteger('total_ingapu_qty')->nullable();

            // ဖြန့်ဝေမှု စုစုပေါင်း
            $table->unsignedInteger('distributed_total')->nullable();

            // ခရိုင်ရုံးယခင်နှစ်လက်ကျန်
            $table->integer('remaining_total')->nullable();

            $table->unique(
                [
                    'academic_year_id',
                    'grade_id',
                    'book_name_id',
                    'guide_type',
                    'sequence_no'
                ],
                'teacher_guide_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_guides');
    }
};
