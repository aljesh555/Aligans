<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} Admin Login</title>
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-8">
                    <h1 class="text-2xl font-bold text-center mb-6 text-gray-800">Admin Login</h1>
                    
                    @if($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <p>{{ $errors->first() }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('filament.admin.auth.login.store') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                            <input id="email" type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="email" value="{{ old('email') }}" required autofocus>
                        </div>

                        <div class="mb-6">
                            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                            <input id="password" type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="password" required>
                        </div>

                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <input id="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" name="remember">
                                <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                                Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 