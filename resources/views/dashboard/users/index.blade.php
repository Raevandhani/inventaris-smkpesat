<x-app-layout>
    <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-400 dark:text-gray-200 leading-tight">
          @foreach ($breadcrumbs as $crumbs)
            @if (!empty($crumbs['url']))
                <a href="{{ $crumbs['url'] }}" class="hover:underline text-gray-800 transition-all duration-200">
                  {{ $crumbs['label'] }}
                </a>
            @else
              <div class="text-gray-800 dark:text-white">
                {{ $crumbs['label'] }}
              </div>
            @endif
              
            @if (!$loop->last)
                &nbsp;>&nbsp;
            @endif
          @endforeach
      </h2>
    </x-slot>

    <div class="px-6 py-4">
        <div class="bg-white dark:bg-gray-800/50 dark:border dark:border-gray-700/50 p-2 mb-2 rounded">
          <form method="GET" action="{{ route('users.index') }}" class="flex items-center justify-between">
            <div class="flex items-center gap-1">
              <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Search..." 
                class="border border-gray-300 dark:border-gray-700/20 dark:bg-gray-800/50 rounded px-4 py-2 dark:text-white"
              >
              <button type="submit" class="bg-sky-700 hover:bg-sky-800 text-white h-10 w-10 rounded flex items-center justify-center transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
              </button>
            </div> 

            <select name="filter" onchange="this.form.submit()" class="border border-gray-300 dark:border-gray-700/20 dark:bg-gray-800/50 rounded dark:text-white">
              <option value="" class="text-gray-400 dark:text-gray-100">Filter by</option>
              <option value="verified" {{ request('filter') == 'verified' ? 'selected' : '' }}>Verified</option>
              <option value="not_verified" {{ request('filter') == 'not_verified' ? 'selected' : '' }}>Not Verified</option>
            </select>
          </form>
        </div>

        @if (session('error'))
          <p class="text-red-500 text-sm mb-2">{{ session('error') }}</p>
        @endif
        @if (session('success'))
          <div class="text-green-500 text-sm mb-2">
              {{ session('success') }}
          </div>
        @endif
        @if (session('deleted'))
          <div class="text-orange-500 text-sm mb-2">
              {{ session('deleted') }}
          </div>
        @endif
        
        @if($edit)
        <div class="bg-white dark:bg-gray-800/50 dark:border dark:border-gray-700/50 mb-3 p-5 rounded shadow-md">
            <form action="{{ route('users.update', $edit->id) }}" method="POST">
                @csrf
                @method('PUT')
            
                <div class="mb-3">
                    <label class="block font-medium dark:text-white/90 mb-1">Verified</label>
                    <select name="is_verified" class="border rounded p-2 w-full dark:bg-gray-800/50 dark:border dark:border-gray-700/50 dark:text-white">
                        <option value="1" {{ $edit->is_verified ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ !$edit->is_verified ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block font-medium dark:text-white/90 mb-1">Roles</label>
                    <select name="roles[]" class="border rounded p-2 w-full dark:bg-gray-800/50 dark:border dark:border-gray-700/50 dark:text-white" multiple>
                        @foreach(\Spatie\Permission\Models\Role::all() as $role)
                            <option value="{{ $role->name }}" 
                                {{ $edit->hasRole($role->name) ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
              
                <div class="flex gap-2">
                  <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-emerald-700 dark:hover:bg-emerald-800 transition-all text-white rounded">Save</button>
                  <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 transition-all text-white rounded">Cancel</a>
              </div>
            </form>
        </div>
        @endif

        <div class="flex flex-col bg-white dark:bg-gray-800/50 shadow-[0px_10px_15px_-3px_rgba(0,_0,_0,_0.1)] border border-gray-200 dark:border-gray-700/50 p-3 rounded">
          <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
              <div class="overflow-y-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                  <thead>
                    <tr>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">#</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Name</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Email</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Roles</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Verified</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($users->whereIn('is_verified', [false, true])->sortBy(fn($b) => array_search($b->status, [false, true])) as $data)
                      <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-xs font-medium text-gray-800 dark:text-white/80">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs font-medium text-gray-800 dark:text-white/80">{{ $data->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs font-medium text-gray-800 dark:text-white/80">{{ $data->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs font-medium text-gray-800 dark:text-white/80">{{ $data->getRoleNames()->join(', ') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs font-medium">
                          <p class="inline-block px-3 py-1 rounded {{ $data->is_verified ? 'bg-green-200 text-green-600 dark:bg-emerald-200 dark:text-emerald-700' : 'bg-red-200 text-red-600 dark:bg-rose-200 dark:text-rose-700'}}">
                            {{ $data->is_verified ? 'Verified' : 'Not Verified' }}
                          </p>
                        </td>
                        <td>
                          <div class="flex items-center gap-1">
                            <a href="{{ route('users.index', ['edit' => $data->id]) }}">
                              <button class="px-3 py-1 text-sm text-white bg-green-600 hover:bg-green-700 rounded">
                                Edit
                              </button>
                            </a>
                            <form action="{{ route('users.destroy', $data->id) }}" method="POST">
                              @csrf
                              @method('DELETE')
                              <button class="px-3 py-1 text-sm text-white bg-red-600 hover:bg-red-700 rounded">Delete</button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    @empty
                    @endforelse
                  </tbody>
                </table>
                @if($users->total() > $n)
                <div class="px-3 pt-2 border-t border-gray-200 ">
                  {{ $users->links() }}
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
    </div>
</x-app-layout>
