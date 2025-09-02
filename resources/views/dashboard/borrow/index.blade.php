
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
        <div class="w-full flex items-center justify-end mb-4">
          <a href="{{ route('borrows.create') }}">
              <button class="px-6 py-2.5 text-white bg-sky-700 hover:bg-sky-800 rounded transition duration-150 text-base font-semibold">
                ADD ITEM
              </button>
          </a>
        </div>
        <div class="flex flex-col bg-white shadow-[0px_10px_15px_-3px_rgba(0,_0,_0,_0.1)] border border-gray-200 p-3 mt-5">
          <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
              <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead>
                    <tr>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">No</th>
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
                    @foreach ($borrows as $data)
                      <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->user?->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->item?->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->location?->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                          {{ $data->borrow_date?->format('d M Y') ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                          @if ($data->return_date)
                              {{ $data->return_date->format('d M Y') }}
                          @else
                              -
                          @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->quantity }}</td>

                        @php
                            $statusClass = match($data->status) {
                                'finished' => 'bg-emerald-100 text-emerald-500',
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
                                  
                                @case('finished')
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
                    @endforeach
                  
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
    </div>
</x-app-layout>
