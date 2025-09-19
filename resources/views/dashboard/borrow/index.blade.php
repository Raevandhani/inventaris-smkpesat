
<x-app-layout>
    <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-400 dark:text-gray-200 leading-tight">
          @foreach ($breadcrumbs as $crumbs)
            @if (!empty($crumbs['url']))
                <a href="{{ $crumbs['url'] }}" class="hover:underline text-gray-800 transition-all duration-200">
                  {{ $crumbs['label'] }}
                </a>
            @else
              <span class="text-gray-800">
                {{ $crumbs['label'] }}
              </span>
            @endif
              
            @if (!$loop->last)
                &nbsp;>&nbsp;
            @endif
          @endforeach
      </h2>
    </x-slot>

    <div class="px-6 py-4">
      <div>
        <div class="w-full flex items-center justify-between mb-3 px-2 py-1.5 bg-gray-50 shadow-md rounded">
          <a href="{{ route('borrows.create') }}">
              <button class="px-5 py-1.5 text-white bg-sky-700 hover:bg-sky-800 rounded transition duration-150 font-semibold">
                Request Borrow
              </button>
          </a>

          <div class="flex items-center gap-2">
            <a href="{{ route('borrows.export.pdf') }}">
                <button class="px-2.5 py-1.5 text-white bg-emerald-600 hover:bg-emerald-700 rounded transition duration-150 font-semibold flex items-center gap-1">
                  Excel
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                  </svg>
                </button>
            </a>
  
            <a href="{{ route('borrows.export.pdf') }}">
                <button class="px-2.5 py-1.5 text-white bg-red-500 hover:bg-red-800 rounded transition duration-150 font-semibold flex items-center gap-1">
                  PDF
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                  </svg>
                </button>
            </a>
          </div>
        </div>

        <div class="flex items-center justify-between">
          <form method="GET" action="{{ route('borrows.index') }}" class="flex items-center gap-1 mb-2">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Search..." 
                class="border border-gray-300 rounded px-4 py-2"
            >
            <button type="submit" class="bg-sky-700 text-white h-10 w-10 rounded flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
              </svg>
            </button>
          </form>

          <div>
            <div class="mb-4">
              <label for="sort" class="mr-2">Sort by:</label>
              <select id="sort" class="border border-gray-300 rounded px-3 py-2"
                  onchange="window.location='{{ route('borrows.index') }}?sort=' + this.value + '{{ request('search') ? '&search=' . request('search') : '' }}'">
                  <option value="latest" {{ $sortOption == 'latest' ? 'selected' : '' }}>Latest</option>
                  <option value="asc" {{ $sortOption == 'asc' ? 'selected' : '' }}>Oldest First</option>
                  <option value="desc" {{ $sortOption == 'desc' ? 'selected' : '' }}>Newest First</option>
                  <option value="az" {{ $sortOption == 'az' ? 'selected' : '' }}>A → Z (Items)</option>
              </select>
            </div>
          </div>
        </div>

      </div>
        <div class="flex flex-col bg-white shadow-[0px_10px_15px_-3px_rgba(0,_0,_0,_0.1)] border border-gray-200 p-3 mt-3">
          <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
              <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead>
                    <tr>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">#</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">User</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Items</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Location</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Borrowed at</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Return at</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Quantity</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Status</th>
                      <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    @forelse ($borrows->sortBy(fn($b) => array_search($b->status, ['pending', 'ongoing', 'done'])) as $data)
                      <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->user?->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->item?->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->location?->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                          {{ $data->borrow_date->timezone('Asia/Jakarta')->format('d M Y | H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                          @if ($data->return_date)
                              {{ $data->return_date->timezone('Asia/Jakarta')->format('d M Y  | H:i') }}
                          @else
                              -
                          @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->quantity }}</td>

                        @php
                            $statusClass = match($data->status) {   
                                'done' => 'bg-emerald-100 text-emerald-500',
                                'ongoing'  => 'bg-sky-100 text-sky-500',
                                default    => 'bg-orange-100 text-orange-500',
                            };
                        @endphp

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                          <span class="px-2 py-1 rounded text-xs font-semibold {{ $statusClass }}">
                              {{ ucfirst($data->status) }}
                          </span>
                        </td>
                        <td>
                          <div class="flex justify-end items-center gap-1 mr-3">
                            @switch($data->status)
                                @case('ongoing')
                                    <a href="{{ route('borrows.edit', $data->id) }}">
                                      <button class="px-5 py-1 text-sm text-white bg-yellow-500 hover:bg-yellow-600 rounded">
                                        Edit
                                      </button>
                                    </a>
                                    <form action="{{ route('borrows.finished', $data->id) }}" method="POST" style="display:inline;">
                                      @csrf
                                      @method('PUT')
                                      <button type="submit" class="px-3 py-1 text-sm text-white bg-green-600 hover:bg-green-700 rounded">
                                        Finish
                                      </button>
                                    </form>
                                    @break
                                  
                                @case('pending')
                                    <form action="{{ route('borrows.accepted', $data->id) }}" method="POST" style="display:inline;">
                                      @csrf
                                      @method('PUT')
                                      <button type="submit" class="px-3 py-1 text-sm text-white bg-green-600 hover:bg-green-700 rounded">
                                          Accept
                                      </button>
                                    </form>

                                    <form action="{{ route('borrows.destroy', $data->id) }}" method="POST">
                                      @csrf
                                      @method('DELETE')
                                      <button class="px-3 py-1 text-sm text-white bg-red-600 hover:bg-red-700 rounded">Decline</button>
                                    </form>
                                    @break
                                  
                                @case('done')
                                    <form action="{{ route('borrows.destroy', $data->id) }}" method="POST">
                                      @csrf
                                      @method('DELETE')
                                      <button class="px-5 py-1 text-sm text-white bg-red-600 hover:bg-red-700 rounded">Delete</button>
                                    </form>
                                    @break
                            @endswitch
                          </div>
                        </td>
                      </tr>
                    @empty
                        <tr>
                          <td colspan="9" class="px-4 py-3 text-center text-gray-500">
                              No result found
                          </td>
                        </tr>
                    @endforelse
                  
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
    </div>
</x-app-layout>
