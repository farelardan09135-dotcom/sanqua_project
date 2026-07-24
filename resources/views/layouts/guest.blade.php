<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

    <div class="relative min-h-screen w-full bg-fixed bg-linear-to-b from-sky-300 via-sky-100 to-blue-50 flex items-center justify-center px-4 py-10 overflow-hidden">

        <!-- Signature ambient glow orbs (fixed, decorative) -->
        <div class="pointer-events-none absolute -top-24 -left-24 w-96 h-96 bg-sky-400/30 rounded-full blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-32 -right-20 w-md h-112 bg-blue-300/40 rounded-full blur-3xl"></div>
        <div class="pointer-events-none absolute top-1/3 right-1/4 w-64 h-64 bg-white/40 rounded-full blur-3xl"></div>

        <div class="relative z-10 w-full flex items-center justify-center">
            {{ $slot }}
        </div>

    </div>

</body>

</html>