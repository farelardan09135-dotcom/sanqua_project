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

    <div class="bg-white rounded-2xl shadow-sm p-5">
        <h2 class="text-base font-bold text-slate-800 mb-3">Riwayat Pembelian</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
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
                        <tr class="hover:bg-slate-50 transition-colors">
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

        @if ($purchases->hasPages())
            <div class="flex items-center justify-center gap-1.5 mt-6 pt-4 border-t border-slate-100">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>

</x-admin-layout>