
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
      <div class="mb-3">
        <div class="w-full flex items-center justify-between mb-3 px-2 py-2.5 bg-gray-50 shadow-md rounded">
          <button id="toggleAdd" class="px-5 py-1.5 text-white bg-sky-700 hover:bg-sky-800 rounded transition duration-150 font-semibold">
            New Maintenance
          </button>

          <form method="GET" class="flex items-center gap-2" id="exportForm">
            <div class="flex items-center gap-1">
              <div class="relative">
                <label for="date1" class="block absolute -top-2.5 text-[8px] font-medium text-gray-500">
                  Export From
                </label>
                <input 
                  type="date" 
                  name="date1" 
                  id="date1"
                  value="{{ request('date1') }}"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg block w-full p-2.5"
                >
              </div>
            
              <div class="relative">
                <label for="date2" class="block absolute -top-2.5 text-[8px] font-medium text-gray-500">
                  To
                </label>
                <input 
                  type="date" 
                  name="date2" 
                  id="date2"
                  value="{{ request('date2') }}"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg block w-full p-2.5"
                >
              </div>
            </div>
          
            <button type="submit" formaction="{{ route('maintains.export.excel') }}" class="px-2.5 py-1.5 text-white bg-emerald-600 hover:bg-emerald-700 rounded transition duration-150 font-semibold flex items-center gap-1">
              Excel
              <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
              </svg>
            </button>
          
            <button type="submit" formaction="{{ route('maintains.export.pdf') }}" class="px-2.5 py-1.5 text-white bg-red-500 hover:bg-red-800 rounded transition duration-150 font-semibold flex items-center gap-1">
              PDF
              <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
              </svg>
            </button>
          </form>
        </div>

        {{-- ADD FORM --}}
        <div id="Add" class="bg-white mb-3 p-5 rounded shadow-md hidden">
          <form action="{{ route('maintains.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid gap-4 grid-cols-2 sm:gap-3">
              <div class="col-span-full">
                <label for="item_id" class="block mb-2 text-sm font-medium text-gray-900">Item Name</label>
                <select id="item_id" name="item_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    <option value="" class="text-gray-400">-- Item --</option>
                    @foreach ($items as $data)
                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                    @endforeach
                </select>
              </div>
              <div>
                <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder="E.g. : 10" required>
              </div>
              <div>
                <label for="start_at" class="block mb-2 text-sm font-medium text-gray-900">
                  Start Time
                </label>
                <input 
                  type="datetime-local" 
                  name="start_at" 
                  id="start_at"
                  value="{{ now()->format('Y-m-d\TH:i') }}" 
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                  required
                >
              </div>
              <div class="col-span-full">
                <label for="notes" class="block mb-2 text-sm font-medium text-gray-900">
                  Notes (Optional)
                </label>
                <textarea name="notes" id="notes" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded block w-full p-2.5" placeholder="E.g. : Broken Parts"></textarea>
              </div>
            </div>
            <button type="submit" class="inline-flex items-center px-5 py-2 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-teal-700 rounded-lg focus:ring-4 hover:bg-teal-800">
              Submit
            </button>
          </form>
        </div>
        {{-- ADD FORM --}}

        {{-- EDIT FORM --}}
        @if($edit)
        <div class="bg-white mb-3 p-5 rounded shadow-md">
          <form action="{{ route('maintains.update', $edit->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid gap-4 grid-cols-2 sm:gap-3">
              <div class="col-span-full">
                <label for="item_id" class="block mb-2 text-sm font-medium text-gray-900">Item Name</label>
                <input type="text" value="{{ $edit->item?->name }}" disabled id="item_id" name="item_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
              </div>
              <div>
                <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900">Quantity</label>
                <input type="number" value="{{ $edit->quantity }}" name="quantity" id="quantity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
              </div>
              <div>
                <label for="start_at" class="block mb-2 text-sm font-medium text-gray-900">
                  Start At
                </label>
                <input 
                  type="datetime-local" 
                  name="start_at" 
                  id="start_at"
                  value="{{ $edit->start_at }}" 
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                  disabled
                  required
                >
              </div>
              <div class="col-span-full">
                <label for="notes" class="block mb-2 text-sm font-medium text-gray-900">
                  Notes (Optional)
                </label>
                <textarea name="notes" id="notes" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded block w-full p-2.5" placeholder="E.g. : Broken Parts">{{ $edit->notes }}</textarea>
              </div>
            </div>
            <button type="submit" class="inline-flex items-center px-5 py-2 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-yellow-500 rounded-lg focus:ring-4 hover:bg-yellow-600">
              Update
            </button>
          </form>
        </div>
        @endif
        {{-- EDIT FORM --}}

        <form method="GET" action="{{ route('maintains.index') }}" class="flex items-center justify-between">
          <div class="flex items-center gap-1">
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
          </div> 
          <select name="sort" onchange="this.form.submit()" class="border border-gray-300 rounded *:">
            <option value="" class="text-gray-400">Sort By</option>
            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
            <option value="largest" {{ request('sort') == 'largest' ? 'selected' : '' }}>Largest</option>
            <option value="smallest" {{ request('sort') == 'smallest' ? 'selected' : '' }}>Smallest</option>
          </select>
        </form>
      </div>

      <div class="flex flex-col bg-white shadow-[0px_10px_15px_-3px_rgba(0,_0,_0,_0.1)] border border-gray-200 p-2">
        <div class="overflow-x-auto">
          <div class="p-1.5 min-w-full inline-block align-middle">
            <div class="overflow-hidden">
              <table class="min-w-full divide-y divide-gray-200">
                <thead>
                  <tr>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">#</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Items</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Quantity</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Notes</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Start at</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Finish at</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  @forelse ($maintains->sortBy(fn($b) => array_search($b->status, [false, true])) as $data)
                    <tr>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $loop->iteration }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->item?->name ?? '-' }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->quantity ?? '-' }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->notes ?? '-' }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                        {{ $data->start_at->timezone('Asia/Jakarta')->format('d M Y - H:i') }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                        @if ($data->finish_at)
                            {{ $data->finish_at->timezone('Asia/Jakarta')->format('d M Y - H:i') }}
                        @else
                            -
                        @endif
                      </td>
                      @php
                          $statusClass = match($data->status) {   
                              1 => 'bg-emerald-100 text-emerald-500',
                              0 => 'bg-orange-100 text-orange-500',
                              default => ''
                          };
                      @endphp
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $statusClass }}">
                            {{ $data->status ? 'Done' : 'In Progress' }}
                        </span>
                      </td>
                      <td>
                        <div class="flex justify-end items-center gap-1 mr-3">
                          @switch($data->status)
                              @case(false)
                                  <a href="{{ route('maintains.index', ['edit' => $data->id]) }}">
                                    <button class="px-5 py-1 text-sm text-white bg-yellow-500 hover:bg-yellow-600 rounded">
                                      Edit
                                    </button>
                                  </a>
                                  <form action="{{ route('maintains.finished', $data->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="px-3 py-1 text-sm text-white bg-green-600 hover:bg-green-700 rounded">
                                      Finish
                                    </button>
                                  </form>
                                  @break
                                
                              @case(true)
                                  <form action="{{ route('maintains.destroy', $data->id) }}" method="POST">
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
              <div class="mt-4">
                {{ $maintains->links() }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
      // Clear Export Date
      document.getElementById('exportForm').addEventListener('submit', function() {
        setTimeout(() => this.reset(), 100);
      });

      // Toggle
      const addForm = document.getElementById("Add");
      const editForm = document.getElementById("Edit");

      document.getElementById("toggleAdd").addEventListener("click", function () {
        addForm.classList.toggle("hidden");
      });
    </script>
</x-app-layout>
