<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Falls du Vite nutzt --}}
</head>
<body class="bg-gradient-to-br from-emerald-100 via-white to-lime-100 min-h-screen flex items-center justify-center text-gray-800">

    <div class="max-w-xl mx-auto px-6 py-12 bg-white rounded-2xl shadow-xl text-center space-y-6">
        <!-- Logo -->
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-20 mx-auto">

        <!-- Beschreibung -->
        <p class="text-gray-600 text-md">
            Die einfache Lösung für digitales Dokumentenmanagement & Unterschriften.
        </p>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4 pt-4">
            <a href="{{ route('login') }}"
               class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-6 py-2 rounded-full shadow transition">
                🔐 Login
            </a>
            <a href="{{ route('register') }}"
               class="bg-white border border-emerald-600 text-emerald-700 hover:bg-emerald-50 font-semibold px-6 py-2 rounded-full shadow transition">
                ✍️ Registrieren
            </a>
        </div>
    </div>

</body>
</html>
