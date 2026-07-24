<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;

class LaporanStokController extends Controller
{
    public function index(Request $request)
    {
        $base = Sparepart::query()
            ->when($request->search, fn ($q, $search) => $q->where('nama', 'like', "%{$search}%"));

        $totalJenisBarang = (clone $base)->count();
        $totalStok = (clone $base)->sum('stok');
        $nilaiInventaris = (clone $base)->selectRaw('SUM(harga * stok) as total')->value('total') ?? 0;
        $jumlahMenipis = (clone $base)->where('stok', '<', 10)->count();
        $jumlahHabis = (clone $base)->where('stok', 0)->count();

        $sort = $request->input('sort', 'stok_asc');

        $query = (clone $base);

        match ($sort) {
            'harga_termurah' => $query->orderBy('harga', 'asc'),
            'harga_termahal' => $query->orderBy('harga', 'desc'),
            'stok_terbanyak' => $query->orderBy('stok', 'desc'),
            'stok_tersedikit' => $query->orderBy('stok', 'asc'),
            default => $query->orderBy('stok', 'asc'),
        };

        $spareparts = $query->paginate(10)->withQueryString();

        return view('owner.stok', compact(
            'spareparts',
            'totalJenisBarang',
            'totalStok',
            'nilaiInventaris',
            'jumlahMenipis',
            'jumlahHabis',
            'sort'
        ));
    }
}