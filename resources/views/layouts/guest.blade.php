<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Anarjo') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        @if (setting('favicon'))
            <link rel="icon" type="image/png" href="{{ asset('storage/' . setting('favicon')) }}">
        @endif
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col">
            <x-public-header />

            <div class="flex-1 flex flex-col justify-center items-center py-12 px-4">
                <div class="w-full sm:max-w-md border border-neutral-200 bg-white p-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
