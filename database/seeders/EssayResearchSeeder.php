<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EssayResearchSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'probondho-gobeshona'],
            ['name' => 'প্রবন্ধ ও গবেষণা']
        );

        $books = [
            ['title' => 'নজরুল জীবনে কুমিল্লা পর্ব', 'author' => 'আহমদ মাওলা', 'price' => 600],
            ['title' => 'বাংলা বানান সংস্কার: বাংলাদেশ', 'author' => 'ড. সাগর রশিদ', 'price' => 750],
            ['title' => 'বাংলাদেশের ফোকলোর চর্চার ইতিবৃত্ত', 'author' => 'ড. মুহম্মদ আবদুল জলিল', 'price' => 750],
            ['title' => 'লোকসংস্কৃতির বিচিত্র ভুবনে', 'author' => 'ড. মুহম্মদ আবদুল জলিল', 'price' => 300],
            ['title' => 'মুজিবনগর সরকার ও বাংলাদেশের মুক্তিযুদ্ধ', 'author' => 'প্রফেসর ড. ফায়েক উজ্জামান', 'price' => 700],
            ['title' => 'ইরান-ইরাক বিরোধ ও সাম্প্রতিক যুদ্ধ', 'author' => 'প্রফেসর ড. ফায়েক উজ্জামান', 'price' => 400],
            ['title' => 'কাব্য চিন্তা', 'author' => 'মন্দাক্রান্তা সেন', 'price' => 120],
            ['title' => 'মোহাম্মদ আলী স্মারকগ্রন্থ', 'author' => 'চৌধুরী মোহাম্মদ আলী', 'price' => 1000],
            ['title' => 'বাঙালির সময়জ্ঞান', 'author' => 'মাধব রায়', 'price' => 250],
            ['title' => 'Crime and Punishment in Ancient India', 'author' => 'Dr. Tarun Kanti Sikder', 'price' => 1000],
            ['title' => 'কালের কথা', 'author' => 'মাহবুবুর রহমান তুহিন', 'price' => 300],
            ['title' => "Values Education in Bangladesh: Understanding High School Graduates' Perspectives", 'author' => 'Dr. Bro. Leo James Pereira', 'price' => 500],
            ['title' => 'Higher Education in Bangladesh', 'author' => 'Subal Lawrence Rozario', 'price' => 1000],
            ['title' => 'Resource Manual For Administrators', 'author' => "Brother Bikaash Victor D' Rozario, CSC", 'price' => 320],
            ['title' => 'The Quest 1.0: Values of Life', 'author' => 'Dr. Bro. Leo James Pereira', 'price' => 300],
            ['title' => 'পঞ্চব্রীহি ধান, জলবায়ু পরিবর্তন ও আবেদ চৌধুরী', 'author' => 'জাকিয়া বেগম', 'price' => 350],
            ['title' => '১৯৭১ গণহত্যা: যশোর উপশহর', 'author' => 'পারভীনা খাতুন', 'price' => 520],
            ['title' => 'ধীরাজ ভট্টাচার্য', 'author' => 'তাপস মজুমদার', 'price' => 250],
            ['title' => 'অবরোধ-বাসিনী', 'author' => 'ড. তানভীর আহমেদ সিডনী', 'price' => 200],
            ['title' => 'মৃত্যুহীন গেরিলা', 'author' => 'শাহ বুলবুল', 'price' => 400],
            ['title' => 'ক্ষুদিরামের কথা', 'author' => 'শাহ বুলবুল', 'price' => 100],
            ['title' => 'কালের খেয়া', 'author' => 'অধ্যাপক ড. রতন সিদ্দিকী', 'price' => 250],
            ['title' => 'অমৃতলালবসুর নাটক ও লোকসংস্কৃতি', 'author' => 'অধ্যাপক ড. রতন সিদ্দিকী', 'price' => 800],
            ['title' => 'ঘুরে দাঁড়ানোর রাজনৈতিক অর্থনৈতিক চিন্তা', 'author' => 'হাসানুল হক ইনু', 'price' => 1000],
            ['title' => 'তিন দাগে ঘেরা বাংলাদেশ', 'author' => 'হাসানুল হক ইনু', 'price' => 350],
            ['title' => 'Bangladeshi Migrant Workers In The Gulf', 'author' => 'কে. এম আতিকুর রহমান', 'price' => 300],
            ['title' => 'বাংলাদেশের মাজার সংস্কৃতি', 'author' => 'ড. ইকবাল হোসেন', 'price' => 500],
            ['title' => 'সময়ের ভাবনা', 'author' => 'সৈয়দ ইশতিয়াক রেজা', 'price' => 150],
            ['title' => 'প্রগতি ও পুনরুত্থান', 'author' => 'কে এম আরিফুজ্জামান', 'price' => 300],
            ['title' => 'নিরন্তরের পথিক', 'author' => 'দীপঙ্কর গৌতম', 'price' => 200],
            ['title' => 'বাংলাদেশের প্রাথমিক শিক্ষা', 'author' => 'মো. আতিয়ার রহমান', 'price' => 150],
            ['title' => 'ঢাকার মঞ্চে ব্রেখটের নাটক', 'author' => 'আরিফ হায়দার', 'price' => 250],
            ['title' => 'বাংলাদেশের মঞ্চ নাটকের আলোচনা ও অন্যান্য', 'author' => 'আরিফ হায়দার', 'price' => 350],
            ['title' => 'বাংলাদেশের মঞ্চনাট্য সমালোচনার স্বরূপ: একুশ শতকের প্রথম দশক', 'author' => 'আরিফ হায়দার', 'price' => 350],
            ['title' => 'করোনা ও অন্যান্য', 'author' => 'মানিক চন্দ্র দাস', 'price' => 150],
            ['title' => 'ডিরোজিও জীবন ও কর্ম', 'author' => 'শক্তিসাধন মুখোপাধ্যায়', 'price' => 800],
            ['title' => 'রেনেসাঁসের আলোকে হেনরি লুই ভিভিয়ান ডিরোজিও', 'author' => 'শক্তিসাধন মুখোপাধ্যায়', 'price' => 930],
            ['title' => 'করোনা যুদ্ধ ও ষড়যন্ত্র তত্ত্ব', 'author' => 'মাহাবুব আলম', 'price' => 250],
            ['title' => 'পথের বাঁকে বিজ্ঞানের ডাকে', 'author' => 'সন্দিপন ধর', 'price' => 480],
            ['title' => 'শতাব্দীর বিজ্ঞান', 'author' => 'তানভীর হোসেন', 'price' => 400],
            ['title' => 'স্মরণীয় দিনপঞ্জি', 'author' => 'সুপা সাদিয়া', 'price' => 984],
            ['title' => 'A Practical Handbook on SPSS', 'author' => 'Sabbir Ahmed Chowdhury', 'price' => 200],
            ['title' => 'সংস্কৃতি সম্প্রীতি ও বাঙালি', 'author' => 'ফাহমিদা হক', 'price' => 200],
            ['title' => 'বঙ্গবন্ধু ও কর্নেল তাহের', 'author' => 'জিয়াউল হক মুক্তা', 'price' => 270],
            ['title' => '১৭ মার্চ ১৯৭৪ হত্যাযজ্ঞের পূর্বাপর', 'author' => 'জিয়াউল হক মুক্তা', 'price' => 200],
            ['title' => 'ইতিহাস আমাকে মুক্তি দেবে', 'author' => 'জিয়াউল হক মুক্তা', 'price' => 440],
            ['title' => 'পানি ও পরিবেশ ১', 'author' => 'মোতাহার হোসেন', 'price' => 400],
            ['title' => 'পরিবেশ ও নারী', 'author' => 'মোতাহার হোসেন', 'price' => 240],
            ['title' => 'নির্বাচিত ১০০ চলচ্চিত্র', 'author' => 'মুম রহমান', 'price' => 800],
            ['title' => 'নির্বাচিত ১০০ চিত্রকলা', 'author' => 'মুম রহমান', 'price' => 700],
            ['title' => 'নির্বাচিত ১০০ আলোকচিত্র', 'author' => 'মুম রহমান', 'price' => 600],
            ['title' => 'সীমানা ও সীমানার বাইরে (প্রথম খণ্ড)', 'author' => 'মেহেদী হাসান', 'price' => 300],
            ['title' => 'সীমানা ও সীমানার বাইরে (দ্বিতীয় খণ্ড)', 'author' => 'মেহেদী হাসান', 'price' => 430],
            ['title' => 'আন্তর্জাতিক রাজনীতি ১', 'author' => 'মেহেদী হাসান', 'price' => 500],
            ['title' => 'বাঙালীর মেধা ও অন্যান্য প্রবন্ধ', 'author' => 'তানভীর মোকাম্মেল', 'price' => 500],
            ['title' => 'ডায়েরী: ১৯৮৫', 'author' => 'তানভীর মোকাম্মেল', 'price' => 500],
            ['title' => 'সিনেমার শিল্পরূপ', 'author' => 'তানভীর মোকাম্মেল', 'price' => 750],
            ['title' => 'চলচ্চিত্রকথা', 'author' => 'তানভীর মোকাম্মেল', 'price' => 280],
            ['title' => 'দ্রোহী কথাসাহিত্যিক আব্দুর রউফ চৌধুরীর জীবন ও সৃষ্টি', 'author' => 'ড. তানভীর আহমেদ সিডনী', 'price' => 900],
            ['title' => 'The tea industry in Bangladesh', 'author' => 'শাহ সোহেল', 'price' => 200],
            ['title' => 'লালনের খোঁজে', 'author' => 'তানভীর মোকাম্মেল', 'price' => 440],
            ['title' => 'চলচ্চিত্র নন্দনতত্ত্ব ও ১২ জন ডিরেক্টর', 'author' => 'তানভীর মোকাম্মেল', 'price' => 300],
            ['title' => 'সাহিত্যের নানাপ্রসঙ্গ ও নান্দনিকতা', 'author' => 'পারভীন আক্তার জেমী', 'price' => 500],
            ['title' => 'ক্রিকেট অভিধান', 'author' => 'ফেরদৌস আরেফিন', 'price' => 200],
            ['title' => 'অবসরের ভাবনা', 'author' => 'আতিকা বেগম রাশি', 'price' => 200],
            ['title' => 'ধামরাই অঞ্চলের ঔপনিবেশিক স্থাপত্য', 'author' => 'শামীমা আক্তার', 'price' => 250],
            ['title' => 'সম্পর্কের পরিচর্যা', 'author' => 'মানিজিঞ্জির স্যান্যাল', 'price' => 300],
            ['title' => 'বাঙালির মুক্তিযুদ্ধ: প্রবন্ধগুচ্ছ', 'author' => 'মোল্লা আমীর হোসেন', 'price' => 150],
            ['title' => 'কথাসাহিত্য: স্বপ্ন বাস্তবতা ও অন্যান্য প্রসঙ্গ', 'author' => 'নাহিদ হক', 'price' => 280],
            ['title' => 'মানিক বন্দ্যোপাধ্যায়ের কথাসাহিত্যে ফ্রয়েডীয় প্রভাব', 'author' => 'ড. উপল তালুকদার', 'price' => 500],
            ['title' => 'সুন্দরবনের পেশাজীবী সম্প্রদায়ের সমাজ ও সংস্কৃতি', 'author' => 'প্রণব কুমার রায়', 'price' => 1000],
            ['title' => 'বাংলাদেশের রাজনীতিতে জাসদ', 'author' => 'জিয়াউল হক মুক্তা', 'price' => 600],
            ['title' => 'হাসান আজিজুল হকের গল্প: শিল্পীর দায় ও শিল্পের জগৎ', 'author' => 'আলমগীর টুলু', 'price' => 300],
            ['title' => 'বিচিত্র বিজ্ঞান', 'author' => 'তানভীর হোসেন', 'price' => 360],
            ['title' => 'দ্বিশতবর্ষে মধুসূদন', 'author' => 'তাপস মজুমদার', 'price' => 400],
            ['title' => 'অপবাদিত রবীন্দ্রনাথ', 'author' => 'ড. রতন সিদ্দিকী', 'price' => 350],
            ['title' => 'রবীন্দ্রনাথের মুসলমান আত্মীয়গণ', 'author' => 'ড. রতন সিদ্দিকী', 'price' => 350],
            ['title' => 'দোগারী', 'author' => 'ইকরামুল হাসান শাকিল', 'price' => 360],
            ['title' => 'সময়ের সংলাপ', 'author' => 'মাহবুবুর রহমান তুহিন', 'price' => 300],
            ['title' => 'রেখার মাঝে', 'author' => 'সৈয়দ ইশতিয়াক রেজা', 'price' => 300],
            ['title' => '১৯৭১: মুক্তিযোদ্ধাদের স্মৃতিকথা', 'author' => 'শফিকুল ইসলাম', 'price' => 250],
            ['title' => 'সিরাজুল আলম খান', 'author' => 'জিয়াউল হক মুক্তা', 'price' => 400],
            ['title' => 'অর্থ জীবিকা এবং অবসর', 'author' => 'শরীফ নুরুল আহকাম', 'price' => 350],
            ['title' => 'রিফ্লেকশন', 'author' => 'এ কে এম আব্দুল্লাহ', 'price' => 300],
            ['title' => 'ইসলাম সবার জন্য', 'author' => 'অধ্যক্ষ (অব.) আল মামুন জাহাঙ্গীরি', 'price' => 360],
            ['title' => 'স্রষ্টার সন্ধানে', 'author' => 'অধ্যক্ষ (অব.) আল মামুন জাহাঙ্গীরি', 'price' => 250],
            ['title' => "Youth's Quest for The Truth", 'author' => 'Md. Al Mamun Jahangiri', 'price' => 250],
            ['title' => 'শিক্ষায় প্রযুক্তি', 'author' => 'সবুজ চৌধুরী', 'price' => 600],
            ['title' => 'Navigating Teenage Mental Health / Mindscape: Adolescence', 'author' => "JOSEPHITE WRITERS' FORUM", 'price' => 800],
        ];

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
        }

        $this->command->info('Seeded '.count($books).' books with authors in "প্রবন্ধ ও গবেষণা" category.');
    }
}
