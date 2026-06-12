<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel 12 Enterprise')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-xl font-bold">Enterprise</a>
                </div>
                @auth
                <div class="flex items-center space-x-4">
                    <span>{{ auth()->user()->name }}</span>
                    <form method="POST" action="/logout">
                        @csrf
                        <button class="text-red-600 hover:text-red-900">Logout</button>
                    </form>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 rounded p-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-red-600">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 rounded p-4 text-green-600">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
