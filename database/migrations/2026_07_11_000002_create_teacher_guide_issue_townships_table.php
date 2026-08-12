<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_guide_issue_townships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_guide_issue_id')->constrained('teacher_guide_issues')->cascadeOnDelete();
            $table->foreignId('township_id')->constrained('townships')->cascadeOnDelete();
            $table->unsignedInteger('issued_quantity')->default(0);
            $table->unsignedInteger('full_package_count')->default(0);
            $table->unsignedInteger('loose_book_count')->default(0);
            $table->timestamps();
            $table->unique(['teacher_guide_issue_id','township_id'], 'tgit_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_guide_issue_townships');
    }
};
