<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Tampilkan daftar semua customer (untuk halaman admin/customer).
     * Bisa difilter berdasarkan nama customer lewat query string ?search=.
     */
    public function index(Request $request)
    {
        $customers = Customer::query()
            ->when($request->search, fn ($q, $search) => $q->where('nama_customer', 'like', "%{$search}%"))
            ->orderBy('nama_customer')
            ->paginate(10)
            ->withQueryString();

        return view('admin.customer', compact('customers'));
    }

    /**
     * Simpan data customer baru dari form tambah customer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_customer' => 'required|string|max:255',
            'no_wa' => 'required|string|max:20',
        ]);

        Customer::create($validated);

        return redirect()->route('admin.customer')->with('status', 'Customer berhasil ditambahkan.');
    }

    /**
     * Perbarui data customer yang sudah ada (nama & no WA).
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'nama_customer' => 'required|string|max:255',
            'no_wa' => 'required|string|max:20',
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customer')->with('status', 'Customer berhasil diperbarui.');
    }

    /**
     * Hapus data customer dari database.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('admin.customer')->with('status', 'Customer berhasil dihapus.');
    }
}