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
        <div class="w-full flex items-center mb-4">
          @if($edit)
              <form action="{{ route('locations.update', $edit->id) }}" method="POST" class="flex items-center gap-2">
                  @csrf
                  @method('PUT')
              
                  <input
                      type="text"
                      name="name"
                      value="{{ old('name', $edit->name) }}"
                      required
                      class="px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                  >
              
                  <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Update</button>
              
                  <a href="{{ route('locations.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">Cancel</a>
              </form>
          @else
              <form action="{{ route('locations.store') }}" method="POST" class="flex items-center gap-2">
                  @csrf
              
                  <input
                      type="text"
                      name="name"
                      value="{{ old('name') }}"
                      placeholder="Enter location name"
                      required
                      class="px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                  >
              
                  <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Add</button>
              </form>
          @endif
        </div>

        <div class="flex flex-col bg-white shadow-[0px_10px_15px_-3px_rgba(0,_0,_0,_0.1)] border border-gray-200 p-3">
          <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
              <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead>
                    <tr>
                      <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase w-16">#</th>
                      <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Name</th>
                      <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Items</th>
                      <th class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase w-32">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($locations as $data)
                      <tr>
                        <td class="px-6 py-4 text-center whitespace-nowrap text-sm font-medium text-gray-800 w-16">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-start whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->name }}</td>
                        <td class="px-6 py-4 text-start whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->borrow_count }}</td>
                        <td>
                          <div class="flex items-center gap-1">
                            <a href="{{ route('locations.index', ['edit' => $data->id]) }}">
                              <button class="px-3 py-1 text-sm text-white bg-green-600 hover:bg-green-700 rounded">
                                Edit
                              </button>
                            </a>
                            <form action="{{ route('locations.destroy', $data->id) }}" method="POST">
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
