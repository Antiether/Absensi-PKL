<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Absensi PKL</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md">

        {{-- LOGO / TITLE --}}
        <div class="text-center mb-6">
            <div class="flex justify-center mb-3">
                <img src="{{ asset('images/logo.png') }}"
                    alt="Logo"
                    class="h-10 w-auto mx-auto mb-2 opacity-90 drop-shadow-sm">
            </div>

            <h1 class="text-2xl font-bold text-gray-800">
                Absensi PKL
            </h1>

            <p class="text-sm text-gray-500">
                Silakan login untuk melanjutkan
            </p>
        </div>

        {{-- CARD --}}
        <div class="bg-white p-6 rounded-xl shadow-sm">

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- EMAIL --}}
                <div>
                    <label class="text-sm text-gray-600">Email</label>
                    <input type="email" name="email"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                {{-- PASSWORD --}}
                <div>
                    <label class="text-sm text-gray-600">Password</label>
                    <input type="password" name="password"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                {{-- REMEMBER --}}
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember">
                    Remember me
                </div>

                {{-- ACTION --}}
                <div class="flex justify-between items-center text-sm">

                    <a href="{{ route('password.request') }}"
                       class="text-gray-500 hover:text-blue-600">
                        Forgot password?
                    </a>

                    <button type="submit"
                        class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-lg">
                        Login
                    </button>

                </div>

            </form>

        </div>

    </div>

</body>
</html>