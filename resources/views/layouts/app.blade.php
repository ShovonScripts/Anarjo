<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Anarjo') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet" />
        @if (setting('favicon'))
            <link rel="icon" type="image/png" href="{{ asset('storage/' . setting('favicon')) }}">
        @endif
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col bg-neutral-50">
            <x-public-header />
            <div class="h-24" aria-hidden="true"></div>

            @if (isset($header))
                <header class="bg-white border-b border-neutral-200">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
