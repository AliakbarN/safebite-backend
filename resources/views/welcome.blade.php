<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAAI - Welcome</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen font-sans">
<div class="max-w-sm w-full p-6 bg-white rounded-2xl shadow-md text-center">
    <!-- Logo -->
    <img src="{{ asset('assets/images/logo.png') }}" alt="MAAI Logo" class="w-20 h-20 mx-auto rounded-full mb-4">

    <!-- Title -->
    <h1 class="text-blue-500 text-2xl font-bold mb-2">Welcome to MAAI</h1>

    <!-- Subtitle -->
    <p class="text-gray-500 text-sm mb-6">Your team, together on one page.</p>

    <!-- Login Button -->
    <a href="{{ route('user.login') }}" class="bg-blue-500 text-white py-2 px-4 rounded-full hover:bg-blue-600 focus:outline-none">Login</a>
</div>
</body>
</html>
