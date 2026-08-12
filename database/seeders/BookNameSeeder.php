<?php

namespace Database\Seeders;

use App\Models\BookName;
use Illuminate\Database\Seeder;

class BookNameSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            'ဗျည်းအက္ခရာကိန်းဂဏန်းတွေ',
            'သင်္ချာ',
            'အင်္ဂလိပ်စာ',
            'စာရိတ္တနှင့်ပြည်သူ့နီတိ',
            'မြန်မာစာ',
            'သိပ္ပံ',
            'ကာယပညာ',
            'သမိုင်း',
            'ဘဝတွက်တာကျွမ်းကျင်စရာ',
        ];

        foreach ($books as $book) {
            BookName::updateOrCreate(
                ['name' => $book],
                ['is_active' => true]
            );
        }
    }
}
