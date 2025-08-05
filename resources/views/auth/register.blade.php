@vite(['resources/css/app.css', 'resources/js/app.js'])

<div class="min-h-screen bg-gray-100 text-gray-900 flex justify-center">
    <div class="max-w-screen-xl m-0 sm:m-10 bg-white shadow sm:rounded-lg flex justify-center flex-1">
        
        <!-- Kiri: Form -->
        <div class="lg:w-1/2 xl:w-5/12 p-6 sm:p-12">
            <div class="mt-12 flex flex-col items-center">
                <h1 class="text-2xl xl:text-3xl font-extrabold">Sign Up</h1>

                <div class="w-full flex-1 mt-8">
                    <!-- Form Register -->
                    <form method="POST" action="{{ route('register') }}" class="mx-auto max-w-xs">
                        @csrf

                        <!-- Name -->
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus
                            placeholder="Full Name"
                            class="w-full px-8 py-4 mb-4 rounded-lg font-medium bg-gray-100 border border-gray-200 
                                   placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white">
                        <x-input-error :messages="$errors->get('name')" class="text-red-500 text-sm mb-2" />

                        <!-- Email -->
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="Email"
                            class="w-full px-8 py-4 mb-4 rounded-lg font-medium bg-gray-100 border border-gray-200 
                                   placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white">
                        <x-input-error :messages="$errors->get('email')" class="text-red-500 text-sm mb-2" />

                        <!-- Password -->
                        <input type="password" name="password" required
                            placeholder="Password"
                            class="w-full px-8 py-4 mb-4 rounded-lg font-medium bg-gray-100 border border-gray-200 
                                   placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white">
                        <x-input-error :messages="$errors->get('password')" class="text-red-500 text-sm mb-2" />

                        <!-- Confirm Password -->
                        <input type="password" name="password_confirmation" required
                            placeholder="Confirm Password"
                            class="w-full px-8 py-4 mb-4 rounded-lg font-medium bg-gray-100 border border-gray-200 
                                   placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="text-red-500 text-sm mb-2" />

                        <!-- Button -->
                        <button type="submit"
                            class="mt-3 tracking-wide font-semibold bg-indigo-500 text-gray-100 w-full py-4 rounded-lg 
                                   hover:bg-indigo-700 transition-all duration-300 ease-in-out flex items-center justify-center">
                            <svg class="w-6 h-6 -ml-2" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                <circle cx="8.5" cy="7" r="4" />
                                <path d="M20 8v6M23 11h-6" />
                            </svg>
                            <span class="ml-3">Register</span>
                        </button>

                        <!-- Links -->
                        <p class="mt-6 text-sm text-gray-600 text-center">
                            Already registered?
                            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Login</a>
                        </p>
                        <p class="text-sm text-gray-600 text-center mt-2">
                            <a href="{{ route('password.request') }}" class="text-indigo-600 hover:underline">Forgot Password?</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kanan: Ganti pakai IMG -->
        <div class="flex-1 bg-indigo-100 text-center hidden lg:flex items-center justify-center">
            <img src="{{ asset('assets/img/ilus.jpg') }}" alt="Illustration" class="max-w-full h-auto p-8">
        </div>
    </div>
</div>
