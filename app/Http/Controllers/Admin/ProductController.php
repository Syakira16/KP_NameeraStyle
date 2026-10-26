<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Color;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk
     */
    public function index()
    {
        $products = Product::with('color')
            ->latest()
            ->get();

        return view('admin.products.index', compact('products'));
    }


    /**
     * Menampilkan form tambah produk
     */
    public function create()
    {
        $colors = Color::orderBy('nama_warna')->get();

        return view('admin.products.create', compact('colors'));
    }


    /**
     * Menyimpan produk baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'color_id' => 'required|exists:colors,id',
            'ukuran' => 'required|in:S,M,L,XL,XXL',
        ]);

        Product::create([
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'color_id' => $request->color_id,
            'ukuran' => $request->ukuran,
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }


    /**
     * Menampilkan form ubah produk
     */
    public function edit(Product $product)
    {
        // Ambil semua warna untuk pilihan dropdown
        $colors = Color::orderBy('nama_warna')->get();

        return view(
            'admin.products.edit',
            compact('product', 'colors')
        );
    }


    /**
     * Memperbarui data produk
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'color_id' => 'required|exists:colors,id',
            'ukuran' => 'required|in:S,M,L,XL,XXL',
        ]);

        $product->update([
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'color_id' => $request->color_id,
            'ukuran' => $request->ukuran,
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }


    /**
     * Menghapus produk
     */
    public function destroy(Product $product)
    {
        // Cek apakah produk sudah pernah digunakan dalam pesanan
        if ($product->orderDetails()->exists()) {

            return redirect()
                ->route('admin.products.index')
                ->with(
                    'error',
                    'Produk tidak dapat dihapus karena sudah digunakan dalam transaksi'
                );
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Produk berhasil dihapus'
            );
    }
}