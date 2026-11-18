<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $writers = [
            'rabindranath-tagore' => [
                'name' => 'রবীন্দ্রনাথ ঠাকুর',
                'books' => ['গীতাঞ্জলি', 'গোরা', 'ঘরে বাইরে', 'শেষের কবিতা'],
            ],
            'nazrul-islam' => [
                'name' => 'কাজী নজরুল ইসলাম',
                'books' => ['বিদ্রোহী', 'অগ্নিবীণা', 'দোলন-চাঁপা', 'ভাঙার গান'],
            ],
            'humayun-ahmed' => [
                'name' => 'হুমায়ূন আহমেদ',
                'books' => ['জনম জনম', 'পারমিতার কাছের বৃক্ষ', 'স্ফুরাদিকের দিন', 'কবিতা'],
            ],
            'zafor-iqbal' => [
                'name' => 'ড. মুহাম্মদ জাফর ইকবাল',
                'books' => ['অমিতাবলীর অগণিত বুধা', 'পথ ও অরণ্য', 'সমিত ও অন্যান্য গল্প'],
            ],
            'women-sarker' => [
                'name' => 'মুনোয়ার সরকার',
                'books' => ['সহসা বিতর্ক', 'বিবলো সুদ্ধ', 'এর্নিতা ও মুহম্মদ'],
            ],
            'sk-mujib-ali' => [
                'name' => 'সৈয়দ মুজতবা আলী',
                'books' => ['চাচা কাহিনী', 'শবনম', 'পান্থপাড়ি'],
            ],
            'syed-shamsul-haque' => [
                'name' => 'সৈয়দ শামসুল হক',
                'books' => ['নূরলদীনুর সারাটা জীবন', 'খেলারাম খেলে', 'একটি সন্ধ্যার বিদায়বেলা'],
            ],
            'akash-hossain' => [
                'name' => 'আকাশ হোসেন',
                'books' => ['শেষ দিনের আলো', 'নিজস্ব ভোর', 'মেঘের পরে মেঘ'],
            ],
            'nadira-sultana' => [
                'name' => 'নাদিরা সুলতানা',
                'books' => ['জলছবি', 'নীরব সন্ধ্যা', 'দূরে কোনো বাতাস'],
            ],
            'kamal-chowdhury' => [
                'name' => 'কামাল চৌধুরী',
                'books' => ['পথের ইশারা', 'বাংলার গান', 'শেষতম পালা'],
            ],
        ];

        foreach ($writers as $slug => $writer) {
            $author = Author::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $writer['name'],
                    'bio' => $writer['name'].' বাংলাদেশের একজন বিশিষ্ট্লেখক ও বক্তাবর্গ। তাঁর লেখাগুলো পাঠকের নিকট অত্যন্ত জনপ্রিয়।',
                    'status' => 'active',
                ]
            );

            foreach ($writer['books'] as $i => $title) {
                Book::firstOrCreate(
                    ['slug' => $slug.'-'.($i + 1)],
                    [
                        'title' => $title,
                        'author_id' => $author->id,
                        'price' => rand(150, 650),
                        'sale_price' => rand(120, 500),
                        'stock' => rand(5, 50),
                        'language' => 'Bengali',
                        'pages' => rand(96, 384),
                        'status' => 'published',
                    ]
                );
            }
        }
    }
}
