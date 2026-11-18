<?php

namespace Database\Seeders;

use App\Models\BookFairEdition;
use Illuminate\Database\Seeder;

class BookFairEditionSeeder extends Seeder
{
    public function run(): void
    {
        BookFairEdition::create([
            'title' => 'অমর একুশে বইমেলা ২০২৬',
            'slug' => 'amar-ekushey-book-fair-2026',
            'tagline' => 'Book Fair 2026 Edition',
            'start_date' => '2026-02-01',
            'end_date' => '2026-02-28',
            'venue' => 'Bangla Academy, Dhaka',
            'is_current' => true,
            'status' => 'published',
        ]);

        BookFairEdition::create([
            'title' => 'Anarjo বসন্ত বই উৎসব ২০২৬',
            'slug' => 'anarjo-basanta-book-festival-2026',
            'tagline' => 'Spring Book Festival 2026',
            'start_date' => '2026-03-15',
            'end_date' => '2026-03-25',
            'venue' => 'Anarjo Publication Hall, Dhaka',
            'is_current' => false,
            'status' => 'published',
        ]);

        BookFairEdition::create([
            'title' => 'আন্তর্জাতিক বইমেলা ২০২৬',
            'slug' => 'international-book-fair-2026',
            'tagline' => 'International Book Fair 2026',
            'start_date' => '2026-05-01',
            'end_date' => '2026-05-10',
            'venue' => 'Suhrawardy Udyan, Dhaka',
            'is_current' => false,
            'status' => 'draft',
        ]);
    }
}
