<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-MWcJ2rROvYtnfQOtI0N3zDgLxL/N8lGm5QJRUfMN3CJm+T2Fum3Y3zX2Lu1UE/1QoAqFvMHYqlXxHdD8yiKeRQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-base-200 text-base-content antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center">
        <div class="flex items-center space-x-2">
            <a href="/" class="text-primary text-4xl font-bold flex items-center gap-2">
                <x-application-logo class="w-12 h-12 text-primary" />
                <span>{{ config('app.name', 'Laravel') }}</span>
            </a>
        </div>

        <div class="w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
