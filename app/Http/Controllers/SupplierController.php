<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Tampilkan daftar semua supplier. Bisa dicari berdasarkan
     * nama supplier lewat query string ?search=.
     */
    public function index(Request $request)
    {
        $suppliers = Supplier::query()
            ->when($request->search, fn ($q, $search) => $q->where('nama_supplier', 'like', "%{$search}%"))
            ->orderBy('nama_supplier')
            ->paginate(10)
            ->withQueryString();

        return view('admin.supplier', compact('suppliers'));
    }

    /**
     * Simpan data supplier baru dari form tambah supplier.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'kontak' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
        ]);

        Supplier::create($validated);

        return redirect()->route('admin.supplier')->with('status', 'Supplier berhasil ditambahkan.');
    }

    /**
     * Perbarui data supplier yang sudah ada (nama, kontak, alamat).
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'kontak' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
        ]);

        $supplier->update($validated);

        return redirect()->route('admin.supplier')->with('status', 'Supplier berhasil diperbarui.');
    }

    /**
     * Hapus data supplier dari database.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('admin.supplier')->with('status', 'Supplier berhasil dihapus.');
    }
}