<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    /**
     * Menampilkan semua data warna
     */
    public function index()
    {
        $colors = Color::withCount('products')
            ->orderBy('nama_warna')
            ->get();

        return view('admin.colors.index', compact('colors'));
    }


    /**
     * Menampilkan form tambah warna
     */
    public function create()
    {
        return view('admin.colors.create');
    }


    /**
     * Menyimpan warna baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_warna' => 'required|string|max:100|unique:colors,nama_warna',
        ], [
            'nama_warna.required' => 'Nama warna wajib diisi.',
            'nama_warna.unique' => 'Warna tersebut sudah tersedia.',
        ]);

        Color::create([
            'nama_warna' => $request->nama_warna,
        ]);

        return redirect()
            ->route('admin.colors.index')
            ->with('success', 'Warna berhasil ditambahkan.');
    }


    /**
     * Menampilkan form edit warna
     */
    public function edit(Color $color)
    {
        return view('admin.colors.edit', compact('color'));
    }


    /**
     * Memperbarui warna
     */
    public function update(Request $request, Color $color)
    {
        $request->validate([
            'nama_warna' => 'required|string|max:100|unique:colors,nama_warna,' . $color->id,
        ], [
            'nama_warna.required' => 'Nama warna wajib diisi.',
            'nama_warna.unique' => 'Warna tersebut sudah tersedia.',
        ]);

        $color->update([
            'nama_warna' => $request->nama_warna,
        ]);

        return redirect()
            ->route('admin.colors.index')
            ->with('success', 'Warna berhasil diperbarui.');
    }


    /**
     * Menghapus warna
     */
    public function destroy(Color $color)
    {
        // Cek apakah warna masih digunakan oleh produk
        if ($color->products()->exists()) {

            return redirect()
                ->route('admin.colors.index')
                ->with(
                    'error',
                    'Warna tidak dapat dihapus karena masih digunakan oleh produk.'
                );
        }

        $color->delete();

        return redirect()
            ->route('admin.colors.index')
            ->with('success', 'Warna berhasil dihapus.');
    }
}