@vite(['resources/css/app.css', 'resources/js/app.js'])

<div class="min-h-screen flex items-center justify-center bg-gray-100 p-4 dark:bg-gray-900">
    <div class="flex flex-col items-center w-full max-w-4xl space-y-8">
        <div class="flex flex-col items-center space-y-2">
            <img src="/assets/logo-smk-light.png" alt="smkpesat-dark" class="w-60">
            <h2 class="text-center font-extrabold dark:font-medium text-sky-900 text-lg dark:text-sky-300">
                Welcome to Pesat Inventory System
            </h2>
        </div>

        <div class="w-full bg-white shadow-xl rounded-lg overflow-hidden px-8 py-4 max-w-lg dark:bg-gray-800 dark:shadow-2xl">

            @auth
                <div class="flex flex-col items-center space-y-5 py-5">
                    <h2 class="text-xl font-bold text-sky-700 dark:text-sky-400">You are already logged in</h2>
                    <a href="{{ route('dashboard') }}"
                        class="block text-center bg-sky-700 text-white py-3 px-5 rounded-lg shadow-md hover:bg-sky-800 transition w-full font-semibold">
                        Go to Dashboard
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit"
                            class="block text-center bg-gray-500 text-white py-3 px-5 rounded-lg shadow-md hover:bg-gray-600 transition w-full font-semibold">
                            Logout
                        </button>
                    </form>
                </div>
            @endauth

            @guest
                @if (session('status'))
                    <div class="mb-4 text-sm text-green-600 dark:text-green-400">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-sky-700 mb-1 dark:text-sky-400">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                            class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-700 focus:border-sky-700 transition dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-sky-500 dark:focus:border-sky-500">
                        @error('error')
                            <p class="text-xs text-orange-500 mt-2 dark:text-orange-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-sky-700 mb-1 dark:text-sky-400">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-700 focus:border-sky-700 transition dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-sky-500 dark:focus:border-sky-500">
                    </div>
                    {{-- <div class="">
                        @if (Route::has('password.request'))
                            <a class="text-sm text-gray-400 hover:text-gray-500 py-2 underline" href="{{ route('password.request') }}">
                                Forgot Password?
                            </a>
                        @endif
                    </div> --}}
                    <div>
                        <div class="bg-white w-full h-3 dark:bg-gray-800"></div>
                        <button type="submit"
                            class="bg-sky-700 font-bold text-white py-3 rounded-lg shadow-md hover:bg-sky-800 transition w-full">
                            Login
                        </button>

                        <p class="text-center text-sm text-gray-500 mt-4 dark:text-gray-400">
                            Don't Have an Account?
                            <a href="{{ route('register') }}" class="text-sky-700 hover:text-sky-900 font-medium underline dark:text-sky-400 dark:hover:text-sky-300">
                                Register
                            </a>
                        </p>
                    </div>
                </form>
            @endguest
        </div>
        <h2 class="dark:text-gray-600 text-xs text-gray-500">[ Responsive Display is still a WORK IN PROGRESS ]</h2>
    </div>
</div>