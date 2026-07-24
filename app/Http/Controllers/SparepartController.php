<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;

/**
 * Mengelola seluruh operasi CRUD untuk data sparepart di halaman Inventory Admin.
 * Mendukung pencarian (search), filter kategori, pengurutan (sort), dan pagination.
 */
class SparepartController extends Controller
{
    /**
     * Menampilkan halaman Inventory beserta daftar sparepart.
     *
     * Mendukung 3 query parameter opsional dari request:
     * - search   : cari berdasarkan nama sparepart (LIKE)
     * - kategori : filter berdasarkan kategori (exact match)
     * - sort     : urutkan berdasarkan harga/stok (lihat match() di bawah)
     *
     * Hasil di-paginate 10 data per halaman, dan withQueryString()
     * memastikan filter yang aktif tetap terbawa saat pindah halaman.
     */
    public function index(Request $request)
    {
        $spareparts = Sparepart::query()
            // Filter: cari berdasarkan nama (hanya jalan kalau ada input 'search')
            ->when($request->search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%");
            })
            // Filter: kategori (hanya jalan kalau ada input 'kategori')
            ->when($request->kategori, function ($query, $kategori) {
                $query->where('kategori', $kategori);
            })
            // Filter: urutan data, tergantung pilihan dropdown 'sort'
            ->when($request->sort, function ($query, $sort) {
                match ($sort) {
                    'harga_tertinggi' => $query->orderByDesc('harga'),
                    'harga_terendah'  => $query->orderBy('harga'),
                    'stok_terbanyak'  => $query->orderByDesc('stok'),
                    'stok_tersedikit' => $query->orderBy('stok'),
                    default           => $query->orderBy('nama'),
                };
            }, function ($query) {
                // Default sort kalau user belum pilih filter 'sort' sama sekali
                $query->orderBy('nama');
            })
            ->paginate(10)
            ->withQueryString();

        // Ambil daftar kategori unik dari data yang sudah ada di database.
        // Otomatis bertambah sendiri setiap kali user input kategori baru
        // lewat modal Tambah — tidak perlu di-hardcode di sini.
        $kategoriList = Sparepart::query()
            ->select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        return view('admin.inventory', compact('spareparts', 'kategoriList'));
    }

    /**
     * Menyimpan sparepart baru ke database (dipanggil dari modal "Tambah").
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|integer|min:0',
        ]);

        Sparepart::create($validated);

        return redirect()
            ->route('admin.inventory')
            ->with('status', 'Sparepart berhasil ditambahkan.');
    }

    /**
     * Memperbarui data sparepart yang sudah ada (dipanggil dari modal "Edit").
     *
     * Laravel otomatis "menemukan" model $sparepart yang sesuai berdasarkan
     * ID di URL berkat Route Model Binding — tidak perlu query manual.
    */
    public function update(Request $request, Sparepart $sparepart)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|integer|min:0',
        ]);

        $sparepart->update($validated);

        return redirect()
            ->route('admin.inventory')
            ->with('status', 'Sparepart berhasil diperbarui.');
    }

    /**
     * Menghapus satu data sparepart dari database.
     * Dipanggil dari tombol ikon tempat sampah di tiap baris tabel.
    */
    public function destroy(Sparepart $sparepart)
    {
        $sparepart->delete();

        return redirect()
            ->route('admin.inventory')
            ->with('status', 'Sparepart berhasil dihapus.');
    }
}