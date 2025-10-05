<x-app-layout>
    <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-400 dark:text-gray-200 leading-tight">
          @foreach ($breadcrumbs as $crumbs)
            @if (!empty($crumbs['url']))
                <a href="{{ $crumbs['url'] }}" class="hover:underline text-gray-800 transition-all duration-200">
                  {{ $crumbs['label'] }}
                </a>
            @else
              <div class="text-gray-800">
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

      <div class="w-full flex items-center justify-between p-2 bg-gray-50 shadow-md rounded mb-3">
        <button id="toggleAdd" class="px-5 py-1.5 text-white bg-sky-700 hover:bg-sky-800 rounded transition duration-150 font-semibold">
          New Roles
        </button>

        <form method="GET" action="{{ route('roles.index') }}" class="flex items-center">
          <select name="sort" onchange="this.form.submit()" class="border border-gray-300 rounded *:">
            <option value="" class="text-gray-400">Sort By</option>
            <option value="most_user" {{ request('sort') == 'most_user' ? 'selected' : '' }}>Most User</option>
            <option value="least_user" {{ request('sort') == 'least_user' ? 'selected' : '' }}>Least User</option>
            <option value="most_perm" {{ request('sort') == 'most_perm' ? 'selected' : '' }}>Most Perms</option>
            <option value="least_perm" {{ request('sort') == 'least_perm' ? 'selected' : '' }}>Least Perms</option>
          </select>
        </form>
      </div>


      <div id="Add" class="bg-white mb-3 p-5 rounded shadow-md hidden">
        <form action="{{ route('roles.store') }}" method="POST" class="flex flex-col gap-4 w-full">
          @csrf
          <input
              type="text"
              name="name"
              value="{{ old('name') }}"
              placeholder="Enter role name"
              required
              class="px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
          >
        
          <div>
              <label class="block font-medium text-sm text-gray-700 mb-2">Permissions</label>
              <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                  @foreach($permissions as $permission)
                      <label class="flex items-center gap-2">
                          <input type="checkbox" name="permissions[]" value="{{ $permission->name }}">
                          <span>{{ $permission->name }}</span>
                      </label>
                  @endforeach
              </div>
          </div>
        
          <div class="flex gap-2">
              <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Add</button>
          </div>
        </form>
      </div>

      @if($editRole)
      <div class="bg-white mb-3 p-5 rounded shadow-md">
        <form action="{{ route('roles.update', $editRole->id) }}" method="POST" class="flex flex-col gap-4 w-full">
          @csrf
          @method('PUT')
      
          <input
              type="text"
              name="name"
              value="{{ old('name', $editRole->name) }}"
              required
              class="px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
          >
      
          <div>
              <label class="block font-medium text-sm text-gray-700 mb-2">Permissions</label>
              <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                  @foreach($permissions as $permission)
                      <label class="flex items-center gap-2">
                          <input type="checkbox" 
                                 name="permissions[]" 
                                 value="{{ $permission->name }}"
                                 {{ $editRole->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                          <span>{{ $permission->name }}</span>
                      </label>
                  @endforeach
              </div>
          </div>
        
          {{-- Buttons --}}
          <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Update</button>
            <a href="{{ route('roles.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">Cancel</a>
          </div>
        </form>
      </div>
      @endif

        <div class="flex flex-col bg-white shadow-[0px_10px_15px_-3px_rgba(0,_0,_0,_0.1)] border border-gray-200 p-3">
          <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
              <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-center">
                  <thead>
                    <tr>
                      <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase w-16">#</th>
                      <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Role Name</th>
                      <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Roles</th>
                      <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Users</th>
                      <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Permissions</th>
                      <th class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase w-32">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($roles as $data)
                      <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 w-16">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ Str::ucfirst($data->name) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->users->count() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->permissions->count() }}</td>
                        
                        <td>
                          <div class="flex items-center gap-1">
                            <a href="{{ route('roles.index', ['edit' => $data->id]) }}">
                              <button class="px-3 py-1 text-sm text-white bg-green-600 hover:bg-green-700 rounded">
                                Edit
                              </button>
                            </a>
                            <form action="{{ route('roles.destroy', $data->id) }}" method="POST">
                              @csrf
                              @method('DELETE')
                              <button class="px-3 py-1 text-sm text-white bg-red-600 hover:bg-red-700 rounded">Delete</button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                @if($roles->total() > $n)
                <div class="px-3 pt-2 border-t border-gray-200 ">
                  {{ $roles->links() }}
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
    </div>
    <script>
      // Toggle
      const addForm = document.getElementById("Add");
      const editForm = document.getElementById("Edit");

      document.getElementById("toggleAdd").addEventListener("click", function () {
        addForm.classList.toggle("hidden");
      });
    </script>

</x-app-layout>
