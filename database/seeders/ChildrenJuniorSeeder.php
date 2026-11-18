<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ChildrenJuniorSeeder extends Seeder
{
    public function run(): void
    {
        $children = Category::firstOrCreate(['slug' => 'shishu-prathomik'], ['name' => 'শিশু/প্রাথমিক']);
        $junior = Category::firstOrCreate(['slug' => 'kishor-madhyamik'], ['name' => 'কিশোর/মাধ্যমিক']);

        $data = [
            'শিশু/প্রাথমিক' => [
                $children,
                [
                    ['title' => 'বাবুদের বর্ণ চেনা', 'author' => 'সৈয়দ শামসুল হক', 'price' => 90],
                    ['title' => 'ইঁদুর ছানার মজার কাণ্ড', 'author' => 'সেলিনা হোসেন', 'price' => 80],
                    ['title' => 'লুকোচুরি এবং আমার মা', 'author' => 'আমীরুল ইসলাম', 'price' => 80],
                    ['title' => 'এক যে পেটুক ফড়িং ছানা', 'author' => 'আমীরুল ইসলাম', 'price' => 100],
                    ['title' => 'আমি ভূত হতে চাই', 'author' => 'আমীরুল ইসলাম', 'price' => 80],
                    ['title' => 'রসগোল্লাটা কথা বলে', 'author' => 'লুৎফর রহমান রিটন', 'price' => 200],
                    ['title' => 'ভূতের সঙ্গে দেখা হলো', 'author' => 'লুৎফর রহমান রিটন', 'price' => 150],
                    ['title' => 'দুই বন্ধুর গল্প', 'author' => 'লুৎফর রহমান রিটন', 'price' => 80],
                    ['title' => 'দাদুর হাতে যাদুর লাঠি', 'author' => 'মঞ্জু সরকার', 'price' => 80],
                    ['title' => 'বাবুইমনি', 'author' => 'দাউদ হায়দার', 'price' => 200],
                    ['title' => 'তুই ভূত মুই ভূত', 'author' => 'ধ্রুব এষ', 'price' => 80],
                    ['title' => 'ফড়িং', 'author' => 'খালেদ হোসাইন', 'price' => 60],
                    ['title' => 'যে দেশে খেলনা নাই', 'author' => 'খালেদ হোসাইন', 'price' => 80],
                    ['title' => 'শান্তির দেশ', 'author' => 'অনীক মাহমুদ', 'price' => 90],
                    ['title' => 'পরীরা কেন আসে না', 'author' => 'আহমদ মাযহার', 'price' => 100],
                    ['title' => 'মায়ের ভালোবাসা', 'author' => 'আসাদ চৌধুরী', 'price' => 90],
                    ['title' => 'মাতৃভক্ত ছেলে', 'author' => 'অধ্যাপক ড. রতন সিদ্দিকী', 'price' => 100],
                    ['title' => "Rat's City tour", 'author' => 'এ কে এম আব্দুল্লাহ', 'price' => 100],
                    ['title' => 'ফিরোজার ফুল', 'author' => 'নিলুফার জাহান', 'price' => 240],
                    ['title' => 'মহাশূন্যের রূপকথা', 'author' => 'মীর সাহারা শৈলী', 'price' => 250],
                    ['title' => 'ময়না পাখির গল্প', 'author' => 'সেলিনা হোসেন', 'price' => 120],
                    ['title' => 'বছরে চার ঋতু', 'author' => 'অনন্ত সাহেদ', 'price' => 150],
                    ['title' => 'কাঠের গাড়ি', 'author' => 'নিলয় নন্দী', 'price' => 150],
                    ['title' => 'আনন্দের দৈত্য', 'author' => 'সফিক রহমান', 'price' => 80],
                    ['title' => 'নিঝুম নদীর জাহাজ', 'author' => 'সফিক রহমান', 'price' => 60],
                    ['title' => 'প্রজাপতি জামা', 'author' => 'মু. মুনিরুজ্জামান', 'price' => 90],
                    ['title' => 'সন্ধ্যা নামে সোনার দেশে', 'author' => 'হাফিজুর রহমান', 'price' => 150],
                    ['title' => 'তালগাছ উঠে গেলো আকাশে', 'author' => 'হাফিজুর রহমান', 'price' => 200],
                    ['title' => 'শেয়ালের শাস্তি', 'author' => 'রাজকন্যা অপরাজিতা লিরিক', 'price' => 150],
                    ['title' => 'সাতরঙা ছবি', 'author' => 'সুকন্যা প্রাচী', 'price' => 135],
                    ['title' => 'ভূত পালালো ভয়ে', 'author' => 'মিতুল সাইফ', 'price' => 100],
                    ['title' => 'পাখির মতো গাই', 'author' => 'মু. মুনিরুজ্জামান', 'price' => 100],
                    ['title' => 'ফুলের মত হাসি', 'author' => 'মু. মুনিরুজ্জামান', 'price' => 100],
                    ['title' => 'টুনটুনি আর রাজার কথা', 'author' => 'উপেন্দ্রকিশোর রায়চৌধুরী', 'price' => 70],
                    ['title' => 'হাতির ভিতর শিয়াল', 'author' => 'উপেন্দ্রকিশোর রায়চৌধুরী', 'price' => 100],
                    ['title' => 'পান্তাবুড়ির কথা', 'author' => 'উপেন্দ্রকিশোর রায়চৌধুরী', 'price' => 120],
                    ['title' => "ইবু'র পোষা কাক", 'author' => 'শামীম আল আমিন', 'price' => 80],
                    ['title' => 'মিতার জন্মদিনে পরী ও সাদাভূত', 'author' => 'মনির হোসেন শাহীন', 'price' => 120],
                    ['title' => 'জাতীয় পতাকার গল্প', 'author' => 'রফিকুর রশিদ', 'price' => 80],
                    ['title' => 'টুকটুকে হলুদ আমটি', 'author' => 'আহমেদ রিয়াজ', 'price' => 80],
                    ['title' => 'লাল মুরগির গম চাষ', 'author' => 'সফিক রহমান', 'price' => 80],
                    ['title' => 'হলুদ নদীর দেশে', 'author' => 'শাহনেওয়াজ চৌধুরী', 'price' => 60],
                    ['title' => 'হাবুলের জলদস্যু জাহাজ', 'author' => 'মাহবুব আজাদ', 'price' => 120],
                    ['title' => 'ছায়াডিঙি', 'author' => 'সাগর রহমান', 'price' => 320],
                    ['title' => 'কালো ভূতের গল্প', 'author' => 'তানভীর আহমেদ সিডনী', 'price' => 100],
                    ['title' => 'সোনা রঙে আমার পড়া (১)', 'author' => 'এম. এম. শামসুজ্জামান', 'price' => 100],
                    ['title' => 'সোনা রঙে আমার পড়া (২)', 'author' => 'এম. এম. শামসুজ্জামান', 'price' => 100],
                    ['title' => "Baby's Alphabet", 'author' => 'সেলিম রেজা', 'price' => 100],
                    ['title' => 'Golden Alphabet', 'author' => 'সেলিম রেজা', 'price' => 100],
                    ['title' => 'Golden Rhymes 1', 'author' => 'ইদ্রিস খান', 'price' => 80],
                    ['title' => 'Golden Rhymes 2', 'author' => 'ইদ্রিস খান', 'price' => 80],
                    ['title' => 'জ্ঞানের রাজ্য ১', 'author' => 'সেলিম রেজা', 'price' => 80],
                    ['title' => 'জ্ঞানের রাজ্য ২', 'author' => 'সেলিম রেজা', 'price' => 80],
                    ['title' => 'ছন্দে ছবিতে গণিত শেখা ১', 'author' => 'এম. এম. শামসুজ্জামান', 'price' => 100],
                    ['title' => 'ছন্দে ছবিতে গণিত শেখা ২', 'author' => 'এম. এম. শামসুজ্জামান', 'price' => 100],
                    ['title' => 'Golden Dictionary 1', 'author' => 'ইদ্রিস খান', 'price' => 80],
                    ['title' => 'Golden Dictionary 2', 'author' => 'ইদ্রিস খান', 'price' => 80],
                    ['title' => 'রঙের খেলা ১', 'author' => 'সুদর্শন বাছার ও সুজাউল আবেদীন', 'price' => 100],
                    ['title' => 'রঙের খেলা ২', 'author' => 'সুদর্শন বাছার ও সুজাউল আবেদীন', 'price' => 100],
                ],
            ],
            'কিশোর/মাধ্যমিক' => [
                $junior,
                [
                    ['title' => 'এক যে ছিল গল্প', 'author' => 'সৈয়দ শামসুল হক', 'price' => 120],
                    ['title' => 'দ্য ওয়ান্ডারফুল স্টোরি অব হনরি সুগার', 'author' => 'সাগর রহমান', 'price' => 0],
                    ['title' => 'মধুকবির গল্প', 'author' => 'তাপস মজুমদার', 'price' => 200],
                    ['title' => 'বোকাইচন্দ্রের ভূত বন্ধুরা', 'author' => 'সফিক রহমান', 'price' => 150],
                    ['title' => 'রক্তচোষা', 'author' => 'শাহনেওয়াজ চৌধুরী', 'price' => 120],
                ],
            ],
        ];

        $totalBooks = 0;
        foreach ($data as $label => [$category, $books]) {
            foreach ($books as $book) {
                $author = Author::firstOrCreate(
                    ['slug' => Str::slug($book['author'])],
                    [
                        'name' => $book['author'],
                        'bio' => $book['author'].' বাংলাদেশের একজন বিশিষ্ট লেখক।',
                        'status' => 'active',
                    ]
                );

                $bookRecord = Book::firstOrCreate(
                    ['slug' => Str::slug($book['title'])],
                    [
                        'title' => $book['title'],
                        'author_id' => $author->id,
                        'price' => $book['price'],
                        'stock' => rand(5, 50),
                        'language' => preg_match('/[a-zA-Z]/', $book['title']) ? 'English' : 'Bengali',
                        'status' => 'published',
                    ]
                );

                $bookRecord->categories()->syncWithoutDetaching([$category->id]);
                $totalBooks++;
            }
        }

        $this->command->info("Seeded {$totalBooks} books across 2 categories: শিশু/প্রাথমিক, কিশোর/মাধ্যমিক");
    }
}
