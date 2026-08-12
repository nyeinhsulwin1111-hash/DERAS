<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('allocation_plan_details', function (Blueprint $table) {

            $table->id();

            $table->foreignId('allocation_plan_id')
                ->constrained('allocation_plans')
                ->cascadeOnDelete();

            /*
             * Student Count
             * Excel G:I
             */
            $table->integer('myanaung_students')
                ->default(0);

            $table->integer('kyankhin_students')
                ->default(0);

            $table->integer('ingapu_students')
                ->default(0);

            /*
             * Allocation
             * Excel K:M
             */

            $table->integer('myanaung_allocation')
                ->default(0);

            $table->integer('kyankhin_allocation')
                ->default(0);

            $table->integer('ingapu_allocation')
                ->default(0);

            /*
             * Full Package / Loose
             */

            $table->integer('myanaung_package')
                ->default(0);

            $table->integer('myanaung_loose')
                ->default(0);

            $table->integer('kyankhin_package')
                ->default(0);

            $table->integer('kyankhin_loose')
                ->default(0);

            $table->integer('ingapu_package')
                ->default(0);

            $table->integer('ingapu_loose')
                ->default(0);

            /*
             * Previous Stock
             */

            $table->integer('myanaung_previous')
                ->default(0);

            $table->integer('kyankhin_previous')
                ->default(0);

            $table->integer('ingapu_previous')
                ->default(0);

            /*
             * Student Total
             */

            $table->integer('myanaung_total_students')
                ->default(0);

            $table->integer('kyankhin_total_students')
                ->default(0);

            $table->integer('ingapu_total_students')
                ->default(0);

            $table->integer('myanaung_transferable')
                ->default(0);

            $table->integer('kyankhin_transferable')
                ->default(0);

            $table->integer('ingapu_transferable')
                ->default(0);

            /*
             * Final Calculation
             */

            $table->integer('myanaung_final')
                ->default(0);

            $table->integer('kyankhin_final')
                ->default(0);

            $table->integer('ingapu_final')
                ->default(0);

            /*
             * Difference
             */

            $table->integer('myanaung_difference')
                ->default(0);

            $table->integer('kyankhin_difference')
                ->default(0);

            $table->integer('ingapu_difference')
                ->default(0);

            $table->integer('total_difference')
                ->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allocation_plan_details');
    }
};
