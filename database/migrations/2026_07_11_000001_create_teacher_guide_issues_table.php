<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_guide_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('grade_id')->constrained('grades')->cascadeOnDelete();
            $table->foreignId('book_name_id')->constrained('book_names')->cascadeOnDelete();
            $table->unsignedInteger('group_no');
            $table->text('group_title');
            $table->string('guide_type');
            $table->unsignedInteger('sequence_no');
            $table->unsignedInteger('district_unit')->default(0);
            $table->unsignedInteger('package_unit')->default(0);
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->unique(['academic_year_id','grade_id','book_name_id','guide_type','sequence_no'], 'tgi_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_guide_issues');
    }
};
