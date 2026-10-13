<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — {{ config('app.name', 'Anarjo') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|Cormorant+Garamond:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'SolaimanLipi', 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
            background: #fafaf9;
            color: #1c1917;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .container {
            max-width: 480px;
            width: 100%;
            text-align: center;
        }
        .error-code {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 8rem;
            font-weight: 700;
            line-height: 1;
            color: #761a2d;
            margin-bottom: 0.5rem;
            letter-spacing: -0.03em;
        }
        .error-title {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 1.75rem;
            font-weight: 600;
            color: #292524;
            margin-bottom: 0.75rem;
        }
        .error-message {
            font-size: 0.95rem;
            color: #78716c;
            line-height: 1.7;
            margin-bottom: 2rem;
        }
        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 2rem;
            background: #1c1917;
            color: #fff;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: background 0.2s;
        }
        .btn-home:hover { background: #44403c; }
        .brand-mark {
            display: inline-block;
            width: 2.5rem;
            height: 2px;
            background: #e34d72;
            margin-bottom: 1.5rem;
        }
        .site-name {
            margin-top: 3rem;
            font-size: 0.8rem;
            color: #a8a29e;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">@yield('code')</div>
        <div class="brand-mark"></div>
        <h1 class="error-title">@yield('title')</h1>
        <p class="error-message">@yield('message')</p>
        <a href="{{ url('/') }}" class="btn-home">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" stroke-linejoin="miter"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Back to Home
        </a>
        <div class="site-name">{{ config('app.name', 'Anarjo') }}</div>
    </div>
</body>
</html>
