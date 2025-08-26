@vite(['resources/css/app.css', 'resources/js/app.js'])

<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-4xl bg-white shadow sm:rounded-lg flex overflow-hidden">
        <div class="w-full p-8 flex flex-col justify-center items-center">
            <img src="/assets/logo-smk-light.png" alt="smkpesat-dark" class="w-40">

            @if (session('status'))
                <div class="mb-4 text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5 w-full">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-blue-500">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                        class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 text-sm focus:outline-none focus:border-blue-400 focus:bg-white">
                    @error('email')
                        <p class="text-sm text-orange-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-blue-500">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 text-sm focus:outline-none focus:border-blue-400 focus:bg-white">
                    @error('password')
                        <p class="text-sm text-orange-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="rounded border-blue-300 text-orange-500 shadow-sm focus:ring-orange-400">
                    <label for="remember_me" class="ml-2 text-sm text-blue-700">Remember Me</label>
                </div>

                <div class="flex items-center justify-between">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-orange-400 hover:text-orange-600" href="{{ route('password.request') }}">
                            Forgot Password?
                        </a>
                    @endif

                    <button type="submit"
                        class="bg-blue-700 font-bold text-white py-2 px-5 rounded-lg shadow-md hover:bg-blue-800 transition">
                        Login
                    </button>
                </div>
            </form>
        </div>

        <div class="hidden md:flex w-full bg-sky-200 items-center justify-center p-8">
            <img src="https://storage.googleapis.com/devitary-image-host.appspot.com/15848031292911696601-undraw_designer_life_w96d.svg"
                 alt="Illustration" class="w-3/4 max-w-sm">
        </div>
    </div>
</div>
