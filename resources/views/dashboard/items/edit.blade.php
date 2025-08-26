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
            <form action="{{ route('items.update', $items->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid gap-4 grid-cols-2 sm:gap-3">
                    <div class="col-span-full">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama Barang</label>
                        <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder="Nama Barang" required value="{{ $items->name }}">
                    </div>
                    <div>
                        <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900">Kategori</label>
                        <select id="category_id" name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                            <option value="{{ $items->category_id }}" class="text-gray-400">{{ $items->category->name }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="total_stock" class="block mb-2 text-sm font-medium text-gray-900">Jumlah Stok</label>
                        <input type="number" name="total_stock" id="total_stock" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder="Stok Barang" required value="{{ $items->total_stock }}">
                    </div>
                    <div>
                        <label for="condition" class="block mb-2 text-sm font-medium text-gray-900">Kondisi</label>
                        <select id="condition" name="condition" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                            <option value="{{ $items->condition }}">{{ $items->condition }}</option>
                            <option value="Good">Good</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Broken">Broken</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                        <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                            <option value="{{ $items->status }}">{{ $items->status }}</option>
                            <option value="Available">Available</option>
                            <option value="Unavailable">Unavailable</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-teal-700 rounded-lg focus:ring-4 hover:bg-teal-800">
                    Update Item
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
