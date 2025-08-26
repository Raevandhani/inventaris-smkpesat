<x-app-layout>
    <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-400 dark:text-gray-200 leading-tight">
          @foreach ($breadcrumbs as $crumbs)
            @if (!empty($crumbs['url']))
                <a href="{{ $crumbs['url'] }}" class="hover:underline text-gray-800 transition-all duration-200">
                    {{ $crumbs['label'] }}
                </a>
            @else
                {{ $crumbs['label'] }}
            @endif
              
            @if (!$loop->last)
                &nbsp;>&nbsp;
            @endif
          @endforeach
      </h2>
    </x-slot>

    <div class="px-6 py-4">
        <div class="w-full flex items-center justify-end mb-4">
          
        </div>

        <div class="flex flex-col bg-white shadow-[0px_10px_15px_-3px_rgba(0,_0,_0,_0.1)] border border-gray-200 p-3">
          <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
              <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead>
                    <tr>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">No</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Name</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Email</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Roles</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    @foreach ($teachers as $data)
                      <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->getRoleNames()->join(', ') }}</td>
                        <td>
                          <div class="flex items-center gap-1">
                            <a href="{{ route('users.edit', $data->id) }}">
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
                    @endforeach
                  
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
    </div>
</x-app-layout>
