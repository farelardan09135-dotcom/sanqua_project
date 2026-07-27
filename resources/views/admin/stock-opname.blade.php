<x-admin-layout :title="'Stock Opname'">

    <h1 class="text-2xl font-bold text-blue-700 mb-2">Stock Opname</h1>
    <p class="text-sm text-slate-500 mb-6">
        Cocokkan stok fisik hasil hitung di gudang dengan stok yang tercatat di sistem.
        Barang yang stok fisiknya sama dengan sistem tidak perlu diubah/dikosongkan.
    </p>

    <div x-data="{ search: '' }">

        {{-- Search (filter tampilan saja, semua data tetap ikut ter-submit) --}}
        <div class="mb-5">
            <div class="flex items-center gap-2 w-full max-w-md h-10 px-4 rounded-xl bg-white border border-slate-200 shadow-sm focus-within:ring-2 focus-within:ring-blue-500/40 focus-within:border-blue-500 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" x-model="search" placeholder="Cari sparepart..."
                    class="w-full h-full text-sm bg-transparent border-none outline-none focus:ring-0 placeholder:text-slate-400">
            </div>
        </div>

        <form method="POST" action="{{ route('admin.stock-opname.store') }}">
            @csrf

            <div class="bg-white rounded-2xl shadow-sm p-5">
                <div class="overflow-x-auto max-h-150 overflow-y-auto">
                    <table class="w-full text-sm">
                        <thead class="sticky top-0 bg-white">
                            <tr class="border-b border-slate-200 text-slate-500">
                                <th class="text-left font-semibold py-2 px-3">Nama Sparepart</th>
                                <th class="text-left font-semibold py-2 px-3">Kategori</th>
                                <th class="text-right font-semibold py-2 px-3">Stok Sistem</th>
                                <th class="text-right font-semibold py-2 px-3">Stok Fisik</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($spareparts as $index => $item)
                                <tr x-show="search === '' || @js($item->nama).toLowerCase().includes(search.toLowerCase())"
                                    class="hover:bg-slate-50 transition-colors">
                                    <td class="py-2 px-3 font-medium text-slate-800">
                                        {{ $item->nama }}
                                        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                    </td>
                                    <td class="py-2 px-3 text-slate-500">{{ $item->kategori ?? '-' }}</td>
                                    <td class="py-2 px-3 text-right text-slate-500">{{ $item->stok }}</td>
                                    <td class="py-2 px-3 text-right">
                                        <input type="number" name="items[{{ $index }}][stok_fisik]" value="{{ $item->stok }}" min="0"
                                            class="w-24 h-9 px-2 text-sm text-right rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5 flex justify-end">
                <button type="submit"
                    class="bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold px-6 py-3 rounded-xl shadow-md transition-all duration-200 active:scale-95">
                    Proses Stock Opname
                </button>
            </div>
        </form>
    </div>

</x-admin-layout>