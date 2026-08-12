<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('grade_book_names', 'category_id')) {
            Schema::table('grade_book_names', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id')->nullable()->after('book_name_id');
            });
        }

        $this->dropAllForeignKeys('grade_book_names');

        $this->dropIndexIfExists('grade_book_names', 'grade_book_unique');
        $this->dropIndexIfExists('grade_book_names', 'grade_book_category_unique');

        Schema::table('grade_book_names', function (Blueprint $table) {
            $table->foreign('grade_id')
                ->references('id')->on('grades')->cascadeOnDelete();
            $table->foreign('book_name_id')
                ->references('id')->on('book_names')->cascadeOnDelete();
            $table->foreign('category_id')
                ->references('id')->on('categories')->nullOnDelete();
            $table->unique(
                ['grade_id', 'book_name_id', 'category_id'],
                'grade_book_category_unique'
            );
        });
    }

    public function down(): void
    {
        $this->dropAllForeignKeys('grade_book_names');
        $this->dropIndexIfExists('grade_book_names', 'grade_book_category_unique');

        if (Schema::hasColumn('grade_book_names', 'category_id')) {
            Schema::table('grade_book_names', function (Blueprint $table) {
                $table->dropColumn('category_id');
            });
        }

        Schema::table('grade_book_names', function (Blueprint $table) {
            $table->foreign('grade_id')
                ->references('id')->on('grades')->cascadeOnDelete();
            $table->foreign('book_name_id')
                ->references('id')->on('book_names')->cascadeOnDelete();
            $table->unique(['grade_id', 'book_name_id'], 'grade_book_unique');
        });
    }

    private function dropAllForeignKeys(string $table): void
    {
        $db = DB::getDatabaseName();
        $rows = DB::select(
            'SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_TYPE = ?',
            [$db, $table, 'FOREIGN KEY']
        );

        foreach ($rows as $row) {
            DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$row->CONSTRAINT_NAME}`");
        }
    }

    private function dropIndexIfExists(string $table, string $index): void
    {
        $db = DB::getDatabaseName();
        $exists = DB::select(
            'SELECT 1 FROM information_schema.STATISTICS
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ? LIMIT 1',
            [$db, $table, $index]
        );

        if ($exists) {
            DB::statement("ALTER TABLE `{$table}` DROP INDEX `{$index}`");
        }
    }
};
