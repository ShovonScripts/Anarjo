<?php

namespace App\Services\Seo;

class SeoService
{
    public static function meta(
        string $title,
        string $description = '',
        string $image = '',
        string $url = '',
        string $type = 'website',
    ): array {
        $url = $url ?: request()->url();
        $appName = config('app.name');

        return [
            'title' => $title,
            'meta_description' => $description,
            'og:title' => $title,
            'og:description' => $description,
            'og:image' => $image ? asset('storage/' . $image) : '',
            'og:url' => $url,
            'og:type' => $type,
            'og:site_name' => $appName,
            'twitter:card' => 'summary_large_image',
            'twitter:title' => $title,
            'twitter:description' => $description,
            'twitter:image' => $image ? asset('storage/' . $image) : '',
            'canonical' => $url,
        ];
    }

    public static function bookSchema(array $book): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Book',
            'name' => $book['title'],
            'author' => $book['author'] ?? null,
            'isbn' => $book['isbn'] ?? null,
            'datePublished' => $book['publish_date'] ?? null,
            'inLanguage' => $book['language'] ?? 'Bengali',
            'numberOfPages' => $book['pages'] ?? null,
            'image' => $book['cover_image'] ? asset('storage/' . $book['cover_image']) : null,
            'offers' => [
                '@type' => 'Offer',
                'price' => $book['sale_price'] ?? $book['price'],
                'priceCurrency' => 'BDT',
                'availability' => $book['stock'] > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            ],
        ];
    }

    public static function breadcrumbSchema(array $items): array
    {
        $itemListElement = [];
        $position = 1;

        foreach ($items as $item) {
            $itemListElement[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $item['name'],
                'item' => $item['url'] ?? null,
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $itemListElement,
        ];
    }
}
