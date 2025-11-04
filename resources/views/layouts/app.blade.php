<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Task Management System') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col bg-gray-100 text-gray-800">
    <nav class="bg-blue-600 text-white shadow">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ url('/') }}" class="font-bold text-lg">
                Task System
            </a>

            <div class="space-x-4">
                @auth
                    <a href="{{ route('tasks.index') }}" class="hover:underline">Daftar Task</a>
                    @if (auth()->user()->hasRole('manager'))
                        <a href="{{ route('tasks.monitor') }}" class="hover:underline">Monitor</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="hover:underline text-red-200">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:underline">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto px-4 py-6">
        @if (session('success'))
            <div id="alert-message"
                class="bg-green-100 text-green-700 border border-green-400 px-4 py-3 rounded mb-4 transition-opacity duration-500">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div id="alert-message"
                class="bg-red-100 text-red-700 border border-red-400 px-4 py-3 rounded mb-4 transition-opacity duration-500">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-blue-600 text-white py-4 text-center">
        <p class="text-sm">&copy; {{ date('Y') }} Task Management System</p>
    </footer>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.getElementById('alert-message');
            if (alert) {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 2000);
            }
        });
    </script>
</body>

</html>
