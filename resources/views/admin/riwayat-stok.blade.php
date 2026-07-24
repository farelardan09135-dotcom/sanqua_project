<x-admin-layout :title="'Riwayat Stok'">

    <h1 class="text-2xl font-bold text-blue-700 mb-6">Riwayat Stok</h1>

    <form method="GET" action="{{ route('admin.riwayat-stok') }}" class="flex flex-wrap items-end gap-3 mb-6">
        <div class="flex-1 min-w-50">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang..."
                class="w-full h-10 px-4 text-sm rounded-lg bg-white border border-slate-200 shadow-sm focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500">
        </div>
        <select name="jenis" onchange="this.form.submit()"
            class="h-10 px-3 text-sm rounded-lg bg-white border border-slate-200 shadow-sm cursor-pointer">
            <option value="">Semua Jenis</option>
            <option value="masuk" @selected(request('jenis') === 'masuk')>Masuk</option>
            <option value="keluar" @selected(request('jenis') === 'keluar')>Keluar</option>
            <option value="penyesuaian" @selected(request('jenis') === 'penyesuaian')>Penyesuaian</option>
        </select>
        <button type="submit" class="h-10 px-4 text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-lg">Cari</button>
    </form>

    <div class="bg-white rounded-2xl shadow-sm p-5">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="text-left font-semibold py-2 px-3">Tanggal</th>
                        <th class="text-left font-semibold py-2 px-3">Barang</th>
                        <th class="text-center font-semibold py-2 px-3">Jenis</th>
                        <th class="text-center font-semibold py-2 px-3">Jumlah</th>
                        <th class="text-left font-semibold py-2 px-3">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($riwayat as $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-2 px-3 text-slate-500">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-2 px-3 font-medium text-slate-800">{{ $item->sparepart->nama ?? '-' }}</td>
                            <td class="py-2 px-3 text-center">
                                @php
                                    $badge = match($item->jenis) {
                                        'masuk' => 'bg-emerald-50 text-emerald-700',
                                        'keluar' => 'bg-red-50 text-red-600',
                                        default => 'bg-amber-50 text-amber-600',
                                    };
                                @endphp
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                    {{ ucfirst($item->jenis) }}
                                </span>
                            </td>
                            <td class="py-2 px-3 text-center font-semibold {{ $item->jenis === 'keluar' ? 'text-red-600' : 'text-emerald-700' }}">
                                {{ $item->jenis === 'keluar' ? '-' : '+' }}{{ $item->jumlah }}
                            </td>
                            <td class="py-2 px-3 text-slate-500">{{ $item->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-8 text-center text-slate-400 italic">Belum ada riwayat stok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($riwayat->hasPages())
            <div class="mt-6 pt-4 border-t border-slate-100">
                {{ $riwayat->links() }}
            </div>
        @endif
    </div>

</x-admin-layout>