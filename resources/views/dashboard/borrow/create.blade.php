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

    <div class="px-12 py-4 mt-4">
        <div class="w-3/4">
        <form action="{{ route('borrows.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
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
            <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-teal-700 rounded-lg focus:ring-4 hover:bg-teal-800">
                Request Pinjam
            </button>
        </form>
        </div>
    </div>
</x-app-layout>
