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
            Create New Item
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
          
            <button type="submit" formaction="{{ route('items.export.excel') }}" class="px-2.5 py-1.5 text-white bg-emerald-600 hover:bg-emerald-700 rounded transition duration-150 font-semibold flex items-center gap-1">
              Excel
              <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
              </svg>
            </button>
          
            <button type="submit" formaction="{{ route('items.export.pdf') }}" class="px-2.5 py-1.5 text-white bg-red-500 hover:bg-red-800 rounded transition duration-150 font-semibold flex items-center gap-1">
              PDF
              <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
              </svg>
            </button>
          </form>
        </div>

        {{-- ADD FORM --}}
        <div id="Add" class="bg-white mb-3 p-5 rounded shadow-md hidden">
            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid gap-4 grid-cols-2 sm:gap-3">
                    <div class="col-span-full">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama Barang</label>
                        <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder="Nama Barang" required>
                    </div>
                    <div>
                        <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900">Kategori</label>
                        <select id="category_id" name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                            <option value="" class="text-gray-400">- Kategori Barang -</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="total_stock" class="block mb-2 text-sm font-medium text-gray-900">Jumlah Stok</label>
                        <input type="number" name="total_stock" id="total_stock" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder="Stok Barang" required>
                    </div>
                    <div>
                        <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                        <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                            <option value="1">Available</option>
                            <option value="0">Unavailable</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-teal-700 rounded-lg focus:ring-4 hover:bg-teal-800">
                    New Item
                </button>
            </form>
        </div>
        {{-- ADD FORM --}}

        {{-- EDIT FORM --}}
        @if($edit)
        <div class="bg-white mb-3 p-5 rounded shadow-md">
            <form action="{{ route('items.update', $edit->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid gap-4 grid-cols-2 sm:gap-3">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama Barang</label>
                        <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder="Nama Barang" required value="{{ $edit->name }}">
                    </div>
                    <div>
                        <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900">Kategori</label>
                        <select id="category_id" name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                            <option value="{{ $edit->category_id }}" class="text-gray-400">{{ $edit->category->name }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="total_stock" class="block mb-2 text-sm font-medium text-gray-900">Jumlah Stok</label>
                        <input type="number" name="total_stock" id="total_stock" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder="Stok Barang" required value="{{ $edit->total_stock }}">
                    </div>
                    <div>
                        <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                        <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                            <option value="1">Available</option>
                            <option value="0">Unavailable</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="inline-flex items-center px-5 py-2 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-teal-700 rounded focus:ring-4 hover:bg-teal-800">
                  Update Item
                </button>
                <a href="{{ route('items.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">Cancel</a>
            </form>
        </div>
        @endif
        {{-- EDIT FORM --}}

        <form method="GET" action="{{ route('items.index') }}" class="flex items-center justify-between">
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

        <div class="flex flex-col bg-white shadow-[0px_10px_15px_-3px_rgba(0,_0,_0,_0.1)] border border-gray-200 p-3">
          <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
              <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead>
                    <tr>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">#</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Category</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Name</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Available</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Unavailable</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Total Stock</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Status</th>
                      <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    @forelse ($items as $data)
                      <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                          {{ $data->category ? $data->category->name : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->available }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->unavailable }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->total_stock }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->status ? 'Available' : 'Unavailable' }}</td>
                        <td>
                          <div class="flex justify-end items-center gap-1">
                            <a href="{{ route('items.index', ['edit' => $data->id]) }}">
                              <button class="px-3 py-1 text-sm text-white bg-green-600 hover:bg-green-700 rounded">
                                Edit
                              </button>
                            </a>
                            <form action="{{ route('items.destroy', $data->id) }}" method="POST">
                              @csrf
                              @method('DELETE')
                              <button class="px-3 py-1 text-sm text-white bg-red-600 hover:bg-red-700 rounded">Delete</button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="8" class="px-4 py-3 text-center text-gray-500">
                            No result found
                        </td>
                      </tr>
                    @endforelse
                  
                  </tbody>
                </table>
                @if($items->total() > $n)
                <div class="px-3 pt-2 border-t border-gray-200 ">
                  {{ $items->links() }}
                </div>
                @endif
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
