@props(['seo' => []])
@if(!empty($seo))
    <meta name="description" content="{{ $seo['meta_description'] ?? '' }}">
    <meta property="og:title" content="{{ $seo['og:title'] ?? '' }}">
    <meta property="og:description" content="{{ $seo['og:description'] ?? '' }}">
    <meta property="og:image" content="{{ $seo['og:image'] ?? '' }}">
    <meta property="og:url" content="{{ $seo['og:url'] ?? '' }}">
    <meta property="og:type" content="{{ $seo['og:type'] ?? 'website' }}">
    <meta property="og:site_name" content="{{ $seo['og:site_name'] ?? '' }}">
    <meta name="twitter:card" content="{{ $seo['twitter:card'] ?? 'summary_large_image' }}">
    <meta name="twitter:title" content="{{ $seo['twitter:title'] ?? '' }}">
    <meta name="twitter:description" content="{{ $seo['twitter:description'] ?? '' }}">
    <meta name="twitter:image" content="{{ $seo['twitter:image'] ?? '' }}">
    <link rel="canonical" href="{{ $seo['canonical'] ?? '' }}">
@endif
