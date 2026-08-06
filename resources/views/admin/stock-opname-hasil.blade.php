<x-admin-layout :title="'Hasil Stock Opname'">

    <h1 class="text-2xl font-bold text-blue-700 mb-6">Hasil Stock Opname</h1>

    @if (empty($hasilOpname))
        <div class="bg-white rounded-2xl shadow-sm p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto mb-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="font-semibold text-slate-700">Tidak ada selisih stok.</p>
            <p class="text-sm text-slate-400 mt-1">Seluruh stok fisik sudah sesuai dengan data di sistem.</p>
        </div>
    @else
        <div class="mb-5 px-4 py-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 text-sm font-medium">
            Ditemukan {{ count($hasilOpname) }} sparepart dengan selisih stok. Data telah diperbarui dan dicatat ke Riwayat Stok.
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500">
                            <th class="text-left font-semibold py-2 px-3">Nama Sparepart</th>
                            <th class="text-right font-semibold py-2 px-3">Stok Sistem (Lama)</th>
                            <th class="text-right font-semibold py-2 px-3">Stok Fisik</th>
                            <th class="text-right font-semibold py-2 px-3">Selisih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($hasilOpname as $row)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-2 px-3 font-medium text-slate-800">{{ $row['nama_sparepart'] }}</td>
                                <td class="py-2 px-3 text-right text-slate-500">{{ $row['stok_sistem'] }}</td>
                                <td class="py-2 px-3 text-right text-slate-500">{{ $row['stok_fisik'] }}</td>
                                <td class="py-2 px-3 text-right font-semibold {{ $row['selisih'] > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ $row['selisih'] > 0 ? '+' : '' }}{{ $row['selisih'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <a href="{{ route('admin.stock-opname') }}" class="inline-block mt-5 text-sm text-blue-600 hover:underline">&larr; Kembali ke Stock Opname</a>

</x-admin-layout>