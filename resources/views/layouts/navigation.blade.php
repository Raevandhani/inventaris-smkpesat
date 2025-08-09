<nav class=" min-h-screen bg-gray-800 border-r border-gray-100 dark:border-gray-700 fixed top-0 left-0 hidden md:block">
    <div class="bg-slate-800 p-6 shadow">
        <a href="{{ route('dashboard') }}">
            <x-application-logo class="block h-9 w-auto fill-current"/>
        </a>
    </div>
    <div class="py-4">
        <h1 class="text-slate-400 font-bold text-sm px-6 mb-2">MENU</h1>
        <ul>
            <li>
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-nav-link>
            </li>
            
            <li>
                <x-nav-link :href="route('items.index')" :active="request()->routeIs('items.index')">
                    {{ __('Inventory') }}
                </x-nav-link>
            </li>
        </ul>
    </div>
</nav>