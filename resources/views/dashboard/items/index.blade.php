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
        <div class="w-full flex items-center justify-end mb-4">
            <a href="{{ route('items.create') }}">
                <button class="px-6 py-2.5 text-white bg-sky-700 hover:bg-sky-800 rounded transition duration-150 text-base font-semibold">
                  TAMBAH BARU
                </button>
            </a>
        </div>

        <div class="flex flex-col bg-white shadow-[0px_10px_15px_-3px_rgba(0,_0,_0,_0.1)] border border-gray-200 p-3">
          <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
              <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead>
                    <tr>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">No</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Nama</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Kategori</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Kondisi</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Tersedia</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Tidak Tersedia</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Jumlah Stock</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Status</th>
                      <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    @foreach ($items as $data)
                      <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->category }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->condition }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->available }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->unavailable }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->total_stock }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->status }}</td>
                        <td>
                          <div class="flex justify-end items-center gap-1">
                            <a href="{{ route('items.edit', $data->id) }}">
                              <button class="px-3 py-1 text-sm text-white bg-green-600 hover:bg-green-700 rounded-lg">
                                Edit
                              </button>
                            </a>
                            <form action="{{ route('items.destroy', $data->id) }}" method="POST">
                              @csrf
                              @method('DELETE')
                              <button class="px-3 py-1 text-sm text-white bg-red-600 hover:bg-red-700 rounded-lg">Delete</button>
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

  <script>
    const body = document.body;
    const modals = {
      add: document.getElementById('addModal'),
      edit: document.getElementById('editModal')
    };

    const triggers = {
      openAdd: document.getElementById('openAddForm'),
      openEdit: document.getElementById('openEditForm'),
      closeAdd: document.getElementById('closeAddForm'),
      closeEdit: document.getElementById('closeEditForm')
    };

    const forms = {
      add: document.getElementById('addform'),
      edit: document.getElementById('editform')
    };

    triggers.openAdd?.addEventListener('click', () => {
      modals.add?.classList.remove('hidden');
      body.style.overflow = 'hidden';
    });

    triggers.openEdit?.addEventListener('click', () => {
      modals.edit?.classList.remove('hidden');
      body.style.overflow = 'hidden';
    });

    triggers.closeAdd?.addEventListener('click', () => {
      modals.add?.classList.add('hidden');
      body.style.overflow = '';
      forms.add?.reset();
    });

    triggers.closeEdit?.addEventListener('click', () => {
      modals.edit?.classList.add('hidden');
      body.style.overflow = '';
      forms.edit?.reset();
    });

    Object.values(modals).forEach(modal => {
      modal?.addEventListener('click', (e) => {
        if (e.target === modal) {
          modal.classList.add('hidden');
          body.style.overflow = '';
        }
      });
    });

  </script>
</x-app-layout>
