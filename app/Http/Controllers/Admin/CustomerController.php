<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function index(Request $request)
    {
        // Kata yang diketik di kolom cari (kosong kalau tidak mencari)
        $cari = $request->cari;

        // Cari kata tersebut di nama, no HP, atau alamat.
        // paginate(20) : tampilkan 20 pelanggan per halaman (bukan semuanya sekaligus)
        // withQueryString() : kata pencarian tidak hilang saat pindah halaman
        $customers = Customer::where('nama', 'like', '%' . $cari . '%')
            ->orWhere('no_hp', 'like', '%' . $cari . '%')
            ->orWhere('alamat', 'like', '%' . $cari . '%')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.customers.index', compact('customers', 'cari'));
    }



    public function create()
    {
        return view('admin.customers.create');
    }



    public function store(Request $request)
    {

        $request->validate($this->aturan(), $this->pesan());



        Customer::create([

            'nama'=>$request->nama,

            'no_hp'=>$request->no_hp,

            'alamat'=>$request->alamat

        ]);


        return redirect()
        ->route('admin.customers.index')
        ->with('success',
        'Data pelanggan berhasil ditambahkan');

    }



    public function edit(Customer $customer)
    {
        return view('admin.customers.edit',
        compact('customer'));
    }



    public function update(Request $request, Customer $customer)
    {

        // Saat ubah data, nomor HP milik pelanggan ini sendiri tidak dianggap kembar
        $request->validate($this->aturan($customer->id), $this->pesan());



        $customer->update([

            'nama'=>$request->nama,

            'no_hp'=>$request->no_hp,

            'alamat'=>$request->alamat

        ]);



        return redirect()
        ->route('admin.customers.index')
        ->with('success',
        'Data pelanggan berhasil diperbarui');

    }



    public function destroy(Customer $customer)
    {

        // Cek apakah customer sudah memiliki pesanan
        if($customer->orders()->exists()){

            return redirect()
            ->route('admin.customers.index')
            ->with(
                'error',
                'Customer tidak dapat dihapus karena sudah memiliki riwayat pesanan'
            );

        }

        $customer->delete();

        return redirect()
        ->route('admin.customers.index')
        ->with(
            'success',
            'Customer berhasil dihapus'
        );

    }


    /**
     * Aturan isian pelanggan.
     *
     * no_hp : hanya angka, 10 sampai 13 digit, dan tidak boleh sama dengan pelanggan lain.
     * $idSendiri dipakai saat ubah data supaya nomor HP milik sendiri tidak dianggap kembar.
     */
    private function aturan($idSendiri = null)
    {

        return [

            'nama'   => 'required|string|max:255',

            'no_hp'  => 'required|digits_between:10,13|unique:customers,no_hp,' . $idSendiri,

            'alamat' => 'required',

        ];

    }


    /**
     * Pesan error dalam bahasa Indonesia.
     */
    private function pesan()
    {

        return [

            'nama.required'         => 'Nama pelanggan wajib diisi.',

            'no_hp.required'        => 'Nomor HP wajib diisi.',
            'no_hp.digits_between'  => 'Nomor HP harus berupa angka sebanyak 10 sampai 13 digit.',
            'no_hp.unique'          => 'Nomor HP sudah terdaftar atas nama pelanggan lain.',

            'alamat.required'       => 'Alamat wajib diisi.',

        ];

    }

}