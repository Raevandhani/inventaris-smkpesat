<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="px-6 py-4">
    @role('admin')
        <div class="mb-3">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                <article class="flex  items-center gap-5 rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/3">
                    <div class="inline-flex h-16 w-16 items-center justify-center rounded-xl bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-white/90">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-800 dark:text-white/90">
                        {{ $totalUsers }}
                        </h3>
                        <p class="flex items-center gap-3 text-gray-500 dark:text-gray-400">
                        Total Users
                        </p>
                    </div>
                </article>

                <article class="flex items-center gap-5 rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/3">
                    <div class="inline-flex h-16 w-16 items-center justify-center rounded-xl bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-white/90">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                        </svg>

                    </div>
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-800 dark:text-white/90">
                        {{ $totalItems }}
                        </h3>
                        <p class="flex items-center gap-3 text-gray-500 dark:text-gray-400">
                        Total Items
                        </p>
                    </div>
                </article>

                <article class="flex items-center gap-5 rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/3">
                    <div class="inline-flex h-16 w-16 items-center justify-center rounded-xl bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-white/90">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.25-8.25-3.286Zm0 13.036h.008v.008H12v-.008Z" />
                        </svg>

                    </div>
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-800 dark:text-white/90">
                        {{ $totalMaintains }}
                        </h3>
                        <p class="flex items-center gap-3 text-gray-500 dark:text-gray-400">
                        Maintenances
                        </p>
                    </div>
                </article>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Currently Borrowed Items</h2>
            <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gray-100 text-gray-700 text-sm">
                    <tr>
                        <th class="px-4 py-2 text-left">Item</th>
                        <th class="px-4 py-2 text-left">Quantity</th>
                        <th class="px-4 py-2 text-left">Location</th>
                        <th class="px-4 py-2 text-left">Borrower</th>
                        <th class="px-4 py-2 text-left">Borrowed On</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($borrowedItems as $data)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $data->item->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $data->quantity }}</td>
                            <td class="px-4 py-2">{{ $data->location->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $data->user->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($data->borrow_date)->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                No items are currently borrowed.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endrole

    @unlessrole('admin')

        @canany('borrow.view' || 'borrow.request')
        <div class="w-full flex items-center justify-between mb-3 px-2 py-2.5 bg-gray-50 shadow-md rounded">
            @can('borrow.request')
            <button id="toggleAdd" class="px-5 py-1.5 text-white bg-sky-700 hover:bg-sky-800 rounded transition duration-150 font-semibold">
              Request Borrow
            </button>
            @endcan

            @can('borrow.view')
            <div>
                <button id="showHistory" class="px-5 py-1.5 text-white bg-blue-600 hover:bg-blue-700 rounded transition duration-150 font-semibold">
                    Show History
                </button>
                <button id="showBorrow" class="px-5 py-1.5 text-white bg-green-600 hover:bg-green-700 rounded transition duration-150 font-semibold hidden">
                    Show Table
                </button>
            </div>
            @endcan
        </div>
        @endcanany

        @can('borrow.request')
        <div id="Add" class="bg-white mb-3 p-5 rounded shadow-md hidden">
          <form action="{{ route('borrows.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="redirect" value="dashboard">
            <div class="grid gap-4 grid-cols-2 sm:gap-3">
              <div>
                <label for="item_id" class="block mb-2 text-sm font-medium text-gray-900">Barang Dipinjam</label>
                <select id="item_id" name="item_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    <option value="" class="text-gray-400">-- Barang --</option>
                    @foreach ($items as $data)
                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                    @endforeach
                </select>
              </div>
              <div>
                <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900">Jumlah Dipinjam</label>
                <input type="number" name="quantity" id="quantity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder="Stok Barang" required>
              </div>
              <div>
                <label for="borrow_date" class="block mb-2 text-sm font-medium text-gray-900">
                  Tanggal Pinjam
                </label>

                <input 
                  type="datetime-local" 
                  name="borrow_date" 
                  id="borrow_date"
                  value="{{ now()->format('Y-m-d\TH:i') }}" 
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                  required
                >
              </div>

              <div>
                  <label for="location_id" class="block mb-2 text-sm font-medium text-gray-900">Lokasi</label>
                  <select id="location_id" name="location_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                      <option value="" class="text-gray-400">Lokasi Peminjaman</option>
                      @foreach ($locations as $data)
                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                      @endforeach
                  </select>
              </div>


            </div>
              <button type="submit" class="inline-flex items-center px-5 py-2 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-teal-700 rounded-lg focus:ring-4 hover:bg-teal-800">
                Submit
              </button>
          </form>
        </div>
        @endcan

        @can('items.view')
        <div class="flex flex-col bg-white shadow-[0px_10px_15px_-3px_rgba(0,_0,_0,_0.1)] border border-gray-200 p-3 mb-3">
          <div class="-m-1.5 overflow-x-auto">
            <div class="px-1.5 py-4 min-w-full inline-block align-middle">
              <div class="overflow-hidden">
                <h2 class="text-xl font-semibold ml-4 mb-4">Available Items</h2>
                <table class="min-w-full divide-y divide-gray-200">
                  <thead>
                    <tr>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">#</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Category</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Name</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Available</th>
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
                <div class="mt-4">
                  {{ $items->links() }}
                </div>
              </div>
            </div>
          </div>
        </div>
        @endcan

        @can('borrow.view')
        <div id="borrow">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Borrowed Items</h2>
                <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 text-gray-700 text-sm">
                        <tr>
                            <th class="px-4 py-2 text-left">#</th>
                            <th class="px-4 py-2 text-left">Items</th>
                            <th class="px-4 py-2 text-left">Location</th>
                            <th class="px-4 py-2 text-left">Quantity</th>
                            <th class="px-4 py-2 text-left">Borrowed At</th>
                            <th class="px-4 py-2 text-left">Return At</th>
                            <th class="px-4 py-2 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse ($borrows->whereIn('status', ['pending', 'ongoing'])->sortBy(fn($b) => array_search($b->status, ['pending', 'ongoing'])) as $data)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2">{{ $data->item->name }}</td>
                                <td class="px-4 py-2">{{ $data->location?->name }}</td>
                                <td class="px-4 py-2">{{ $data->quantity }}</td>
                                <td class="px-4 py-2">{{ $data->borrow_date->timezone('Asia/Jakarta')->format('d M Y - H:i') }}</td>
                                <td class="px-4 py-2">
                                    @if ($data->return_date)
                                        {{ $data->return_date->timezone('Asia/Jakarta')->format('d M Y - H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                @php
                                    $statusClass = match($data->status) {   
                                        'ongoing'  => 'bg-sky-100 text-sky-500',
                                        default    => 'bg-orange-100 text-orange-500',
                                    };
                                @endphp
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $statusClass }}">
                                        {{ ucfirst($data->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-3 text-center text-gray-500">
                                    No items are currently borrowed.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
              {{ $borrows->links() }}
            </div>
        </div>

        {{-- History --}}
        <div class="hidden" id="history">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">History</h2>
                <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 text-gray-700 text-sm">
                        <tr>
                            <th class="px-4 py-2 text-left">#</th>
                            <th class="px-4 py-2 text-left">Items</th>
                            <th class="px-4 py-2 text-left">Location</th>
                            <th class="px-4 py-2 text-left">Quantity</th>
                            <th class="px-4 py-2 text-left">Borrowed At</th>
                            <th class="px-4 py-2 text-left">Return At</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse ($borrows->whereIn('status', ['declined', 'done'])->sortBy(fn($b) => array_search($b->status, ['declined', 'done'])) as $data)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2">{{ $data->item->name }}</td>
                                <td class="px-4 py-2">{{ $data->location?->name }}</td>
                                <td class="px-4 py-2">{{ $data->quantity }}</td>
                                <td class="px-4 py-2">{{ $data->borrow_date->timezone('Asia/Jakarta')->format('d M Y - H:i') }}</td>
                                <td class="px-4 py-2">
                                    @if ($data->return_date)
                                        {{ $data->return_date->timezone('Asia/Jakarta')->format('d M Y - H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                @php
                                    $statusClass = match($data->status) {   
                                        'done' => 'bg-emerald-100 text-emerald-500',
                                        'declined'  => 'bg-red-100 text-red-500',
                                        default    => '',
                                    };
                                @endphp
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $statusClass }}">
                                        {{ ucfirst($data->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex justify-end items-center gap-1 mr-3">
                                        <form action="{{ route('borrows.destroy', $data->id) }}" method="POST">
                                          @csrf
                                          @method('DELETE')
                                          <button class="px-5 py-1 text-sm text-white bg-red-600 hover:bg-red-700 rounded">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-3 text-center text-gray-500">
                                    No History Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
              {{ $borrows->links() }}
            </div>
        </div>
        @endcan
    @endunlessrole

    @if (!$hasPermission)
        <div class="flex flex-col items-center justify-center py-20 text-gray-500 dark:text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M12 9v2m0 4h.01M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0z" />
            </svg>
            <p class="text-lg font-semibold">No Permissions Assigned</p>
            <p class="text-sm">You currently don't have access to any feature</p>
        </div>
    @endif
    </div>

    <script>
        const addForm = document.getElementById("Add");
        document.getElementById("toggleAdd").addEventListener("click", function () {
          addForm.classList.toggle("hidden");
        });

        const showHistory = document.getElementById("showHistory");
        const showBorrow = document.getElementById("showBorrow");
        const borrow = document.getElementById("borrow");
        const history = document.getElementById("history");

        function toggleView() {
          borrow.classList.toggle("hidden");
          history.classList.toggle("hidden");
          showHistory.classList.toggle("hidden");
          showBorrow.classList.toggle("hidden");
        }

        showHistory.addEventListener("click", toggleView);
        showBorrow.addEventListener("click", toggleView);
    </script>
</x-app-layout>
