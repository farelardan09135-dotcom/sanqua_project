<x-admin-layout :title="'Detail Pembelian'">

    <h1 class="text-xl font-bold text-blue-700 mb-4">{{ $purchase->no_pembelian }}</h1>

    <div class="bg-white rounded-2xl shadow-sm p-6 max-w-2xl">
        <div class="grid grid-cols-2 gap-4 mb-5 text-sm">
            <div>
                <p class="text-slate-500">Supplier</p>
                <p class="font-semibold text-slate-800">{{ $purchase->supplier->nama_supplier ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-500">Tanggal</p>
                <p class="font-semibold text-slate-800">{{ $purchase->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="divide-y divide-slate-100 border-y border-slate-200 py-2 mb-4">
            @foreach ($purchase->items as $item)
                <div class="flex items-center justify-between py-2.5 text-sm">
                    <div>
                        <p class="font-medium text-slate-700">{{ $item->sparepart->nama_sparepart ?? 'Barang dihapus' }}</p>
                        <p class="text-xs text-slate-400">{{ $item->qty }} × Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</p>
                    </div>
                    <span class="font-semibold text-slate-700">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <div class="flex items-center justify-between">
            <span class="font-bold text-slate-800">Total</span>
            <span class="text-lg font-bold text-blue-700">Rp {{ number_format($purchase->total_pembelian, 0, ',', '.') }}</span>
        </div>
    </div>

    <a href="{{ route('admin.pembelian') }}" class="inline-block mt-4 text-sm text-blue-600 hover:underline">← Kembali ke Riwayat Pembelian</a>

</x-admin-layout>