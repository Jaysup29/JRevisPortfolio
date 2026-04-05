<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'Portfolio - Jay-ar Revis' }}</title>

    <!-- SEO Meta -->
    <meta name="description" content="Jay-ar Revis - Full Stack Web Developer specializing in Laravel, Livewire, and modern web technologies. Creating exceptional digital experiences.">
    <meta name="keywords" content="Laravel Developer, PHP Developer, Livewire, Full Stack Developer, Web Developer Philippines">
    <meta name="author" content="Jay-ar Revis">

    <!-- Open Graph -->
    <meta property="og:title" content="JAY-AR REVIS - Full Stack Developer">
    <meta property="og:description" content="Full Stack Web Developer specializing in Laravel and modern web technologies">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Inline Custom Styles -->
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.1); }
        ::-webkit-scrollbar-thumb { background: rgba(0, 0, 0, 0.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(0, 0, 0, 0.5); }
    </style>

    <!-- Dark mode applied before paint to prevent flash -->
    <script>
        (function() {
            // Auto dark mode: 6PM-6AM Manila time, unless user manually toggled
            function isManilaNight() {
                var now = new Date(new Date().toLocaleString('en-US', { timeZone: 'Asia/Manila' }));
                var hour = now.getHours();
                return hour >= 18 || hour < 6;
            }

            var userPref = localStorage.getItem('darkMode');
            var isDark;
            if (userPref !== null) {
                isDark = userPref === 'true';
            } else {
                isDark = isManilaNight();
            }

            if (isDark) {
                document.documentElement.classList.add('dark');
            }
            var s = document.createElement('style');
            s.textContent = 'html, body { background-color: ' + (isDark ? '#0a0a14' : '#f0f1f5') + ' !important; }';
            document.head.appendChild(s);
            window.__bgStyle = s;
        })();
    </script>
</head>
<body class="antialiased">
    <!-- Background Effects -->
    <div class="morph-bg" aria-hidden="true">
        <div class="morph-blob morph-blob-1"></div>
        <div class="morph-blob morph-blob-2"></div>
        <div class="morph-blob morph-blob-3"></div>
    </div>
    <div class="film-grain" aria-hidden="true"></div>
    <div class="cursor-spotlight" id="cursorSpotlight" aria-hidden="true"></div>
    <div id="guest-layout" class="min-h-screen flex flex-col">
        <!-- Page Content -->
        <main class="flex-1">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts

    <!-- Custom Scripts -->
    <script>
        window.portfolioConfig = {
            autoFlipInterval: 25000,
            flipDuration: 4000,
            darkModeKey: 'portfolio_dark_mode'
        };
    </script>
</body>
</html>
