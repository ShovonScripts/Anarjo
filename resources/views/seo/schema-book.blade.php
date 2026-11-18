@props(['book'])
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "Book",
    "name": "{{ $book->title }}",
    "author": {
        "@type": "Person",
        "name": "{{ $book->author?->name ?? '' }}"
    },
    "isbn": "{{ $book->isbn ?? '' }}",
    "datePublished": "{{ $book->publish_date?->format('Y-m-d') ?? '' }}",
    "inLanguage": "{{ $book->language ?? 'Bengali' }}",
    "numberOfPages": {{ $book->pages ?? 0 }},
    "image": "{{ $book->cover_image ? asset('storage/' . $book->cover_image) : '' }}",
    "offers": {
        "@type": "Offer",
        "price": {{ $book->sale_price ?? $book->price }},
        "priceCurrency": "BDT",
        "availability": "{{ $book->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}"
    }
}
</script>
