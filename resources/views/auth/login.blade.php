<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    @vite('resources/css/app.css')
</head>

<body>
    <div class="bg-gray-50">
        <div class="min-h-screen flex flex-col items-center justify-center py-6 px-4">
            <div class="max-w-[480px] w-full">
                <div class="p-6 sm:p-8 rounded-2xl bg-white border border-gray-200 shadow-sm">
                    <h1 class="text-slate-900 text-center text-3xl font-semibold">Sign in</h1>

                    @if ($errors->any())
                        <div class="bg-red-100 text-red-700 p-3 rounded-md text-sm mt-4">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div>
                            <label class="text-slate-900 text-sm font-medium mb-2 block">User name</label>
                            <div class="relative flex items-center">
                                <input name="username" type="text" required value="{{ old('username') }}"
                                    class="w-full text-slate-900 text-sm border border-slate-300 px-4 py-3 pr-8 rounded-md outline-blue-600"
                                    placeholder="Enter user name" />
                            </div>
                        </div>

                        <div>
                            <label class="text-slate-900 text-sm font-medium mb-2 block">Password</label>
                            <div class="relative flex items-center">
                                <input name="password" type="password" required
                                    class="w-full text-slate-900 text-sm border border-slate-300 px-4 py-3 pr-8 rounded-md outline-blue-600"
                                    placeholder="Enter password" />
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center">
                                <input id="remember-me" name="remember" type="checkbox"
                                    class="h-4 w-4 shrink-0 text-blue-600 focus:ring-blue-500 border-slate-300 rounded" />
                                <label for="remember-me" class="ml-3 block text-sm text-slate-900">
                                    Remember me
                                </label>
                            </div>
                        </div>

                        <div class="!mt-8">
                            <button type="submit"
                                class="w-full py-2 px-4 text-[15px] font-medium tracking-wide rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none cursor-pointer">
                                Sign in
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
