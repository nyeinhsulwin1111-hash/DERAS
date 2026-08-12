<?php

namespace Database\Seeders;

use App\Models\Township;
use Illuminate\Database\Seeder;

class TownshipSeeder extends Seeder
{
    public function run(): void
    {
        $townships = [
            'မြန်အောင်',
            'ကြံခင်း',
            'အင်္ဂပူ',
        ];

        foreach ($townships as $township) {
            Township::updateOrCreate([
                'name' => $township,
            ], [
                'is_active' => true,
            ]);
        }
    }
}
