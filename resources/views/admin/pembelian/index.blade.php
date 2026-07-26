<x-admin-layout :title="'Pembelian'">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-blue-700">Pembelian</h1>
        <a href="{{ route('admin.pembelian.create') }}"
            class="flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-md transition-all duration-200 active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Pembelian
        </a>
    </div>

    @if (session('status'))
        <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 text-sm font-medium">
            {{ session('status') }}
        </div>
    @endif

    <div x-data="{ search: '' }">

        {{-- Search Bar --}}
        <div class="mb-4">
            <div class="flex items-center gap-2 w-full max-w-md h-10 px-4 rounded-xl bg-white border border-slate-200 shadow-sm focus-within:ring-2 focus-within:ring-blue-500/40 focus-within:border-blue-500 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" x-model="search" placeholder="Cari no. pembelian atau supplier..."
                    class="w-full h-full text-sm bg-transparent border-none outline-none focus:ring-0 placeholder:text-slate-400">
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h2 class="text-base font-bold text-slate-800 mb-3">Riwayat Pembelian</h2>

            <div class="overflow-x-auto max-h-150 overflow-y-auto">
                <table class="w-full text-sm">
                    <thead class="sticky top-0 bg-white">
                        <tr class="border-b border-slate-200 text-slate-500">
                            <th class="text-left font-semibold py-2 px-3">No Pembelian</th>
                            <th class="text-left font-semibold py-2 px-3">Tanggal</th>
                            <th class="text-left font-semibold py-2 px-3">Supplier</th>
                            <th class="text-left font-semibold py-2 px-3">Admin</th>
                            <th class="text-left font-semibold py-2 px-3">Total</th>
                            <th class="text-center font-semibold py-2 px-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($purchases as $item)
                            <tr x-show="search === ''
                                    || @js($item->no_pembelian).toLowerCase().includes(search.toLowerCase())
                                    || @js($item->supplier->nama_supplier ?? '').toLowerCase().includes(search.toLowerCase())"
                                class="hover:bg-slate-50 transition-colors">
                                <td class="py-2 px-3 font-medium text-slate-800">{{ $item->no_pembelian }}</td>
                                <td class="py-2 px-3 text-slate-500">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                <td class="py-2 px-3 text-slate-500">{{ $item->supplier->nama_supplier ?? '-' }}</td>
                                <td class="py-2 px-3 text-slate-500">{{ $item->user->name ?? '-' }}</td>
                                <td class="py-2 px-3 text-slate-700">Rp {{ number_format($item->total_pembelian, 0, ',', '.') }}</td>
                                <td class="py-2 px-3 text-center">
                                    <a href="{{ route('admin.pembelian.show', $item->id) }}" class="text-blue-600 hover:underline text-xs font-medium">Lihat Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-8 text-center text-slate-400 italic">Belum ada riwayat pembelian.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</x-admin-layout>