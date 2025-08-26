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

            <li x-data="{ open: {{ request()->routeIs('users.*') ? 'true' : 'false' }} }" class="relative">
                <button 
                    @click="open = !open" 
                    class="flex items-center justify-between w-full px-6 py-1.5 text-gray-500 hover:text-gray-200 hover:bg-white/15 animation transition duration-250"
                    :class="open ? 'bg-white/20 text-white border-l-2 border-white' : 'text-gray-500 hover:text-gray-200 hover:bg-white/15 '"
                >
                    <div class="flex items-center gap-2">
                        <span>{{ __('Users') }}</span>
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 ml-2 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <ul 
                    x-show="open" 
                    x-transition 
                    class=""
                >
                    <li class="opacity-70">
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                            {{ __('All') }}
                        </x-nav-link>
                    </li>
                    <li class="opacity-70">
                        <x-nav-link :href="route('users.students')" :active="request()->routeIs('users.students')">
                            {{ __('Student') }}
                        </x-nav-link>
                    </li>
                    <li class="opacity-70">
                        <x-nav-link :href="route('users.teachers')" :active="request()->routeIs('users.teachers')">
                            {{ __('Teacher') }}
                        </x-nav-link>
                    </li>
                </ul>
            </li>


            <li>
                <x-nav-link :href="route('items.index')" :active="request()->routeIs('items.index')">
                    {{ __('Inventory') }}
                </x-nav-link>
            </li>

            <li>
                <x-nav-link :href="route('borrows.index')" :active="request()->routeIs('borrows.index')">
                    {{ __('Borrow') }}
                </x-nav-link>
            </li>
        </ul>
    </div>

    <div class="bg-slate-600 w-full h-[0.2px]"></div>

    <div class="py-4">
        <h1 class="text-slate-400 font-bold text-sm px-6 mb-2">ADMINISTRATION</h1>
        <ul>
            <li>
                <x-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')">
                    {{ __('Roles') }}
                </x-nav-link>
            </li>
            
            <li>
                <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.index')">
                    {{ __('Categories') }}
                </x-nav-link>
            </li>
        </ul>
    </div>
</nav>