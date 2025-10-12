<x-guest-layout>
    <div class="mb-4 text-lg text-gray-600 dark:text-gray-400">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address to the Admin.') }}
    </div>

    <div class="mt-5 flex items-center">
        <a href="/">
            <x-primary-button class="scale-110 bg-sky-700 dark:bg-sky-700 dark:text-white dark:hover:text-white/50 hover:bg-sky-800">
                {{ __('Back') }}
            </x-primary-button>
        </a>
    </div>
</x-guest-layout>
