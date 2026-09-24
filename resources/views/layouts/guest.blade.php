<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Muni University News & Media Portal') }} - {{ $title ?? 'Login' }}</title>

        <!-- Fonts - Muni Branding -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700;900&family=Source+Sans+Pro:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Font Awesome 6.4 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root {
                --muni-red: #8B0000;
                --muni-red-dark: #5C0000;
                --muni-gold: #ffde00;
                --muni-blue: #24AAE1;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased" style="font-family: 'Source Sans Pro', system-ui, sans-serif;">
        <!-- Muni Top Bar -->
        <div class="w-full text-white text-sm py-2 px-4" style="background: var(--muni-red-dark);">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <span><i class="fa-solid fa-location-dot me-2"></i>Arua City, Uganda</span>
                <span>news.muni.ac.ug</span>
            </div>
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0" style="background: #f7f9fc;">
            <!-- Logo & Branding -->
            <div class="text-center mb-6">
                <a href="/" class="inline-block">
                    <img src="/assets/images/muni-logo.png" alt="Muni University Logo" class="h-20 mx-auto mb-3" style="max-height: 80px;">
                </a>
                <h1 class="text-2xl font-bold" style="font-family: 'Merriweather', Georgia, serif; color: var(--muni-red);">Muni University</h1>
                <p class="text-sm tracking-widest uppercase font-semibold" style="color: var(--muni-red-dark);">Transforming Lives</p>
                <p class="text-xs mt-1" style="color: var(--muni-blue);">News & Media Portal</p>
            </div>

            <div class="w-full sm:max-w-md mt-2 px-8 py-8 bg-white shadow-lg overflow-hidden sm:rounded-sm" style="border-top: 4px solid var(--muni-gold); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
                {{ $slot }}
            </div>

            <p class="mt-6 text-xs text-gray-500">&copy; {{ date('Y') }} Muni University. All rights reserved.</p>
        </div>
    </body>
</html>
