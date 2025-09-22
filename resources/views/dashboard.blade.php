<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    
    <div class="px-6 py-4">
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
    </div>

</x-app-layout>
