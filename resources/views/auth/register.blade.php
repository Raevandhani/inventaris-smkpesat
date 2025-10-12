<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" class="text-sky-800 dark:text-sky-400"/>
            <x-text-input id="name" class="block mt-1 w-full focus:outline-none focus:ring-2 focus:ring-sky-700 focus:border-sky-700 transition dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-sky-500 dark:focus:border-sky-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Username"/>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" class="text-sky-800 dark:text-sky-400"/>
            <x-text-input id="email" class="block mt-1 w-full focus:outline-none focus:ring-2 focus:ring-sky-700 focus:border-sky-700 transition dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-sky-500 dark:focus:border-sky-500" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="info@example.com"/>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-sky-800 dark:text-sky-400"/>

            <x-text-input id="password" class="block mt-1 w-full focus:outline-none focus:ring-2 focus:ring-sky-700 focus:border-sky-700 transition dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-sky-500 dark:focus:border-sky-500"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="Min. 8 Characters"/>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sky-800 dark:text-sky-400"/>

            <x-text-input id="password_confirmation" class="block mt-1 w-full focus:outline-none focus:ring-2 focus:ring-sky-700 focus:border-sky-700 transition dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-sky-500 dark:focus:border-sky-500"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password"/>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-sky-800 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ url('/') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4 bg-sky-700 dark:bg-sky-700 hover:bg-sky-800 dark:hover:bg-sky-800 dark:text-white dark:hover:text-gray-400">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
