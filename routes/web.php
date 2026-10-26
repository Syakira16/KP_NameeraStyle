<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Admin\ReportController;

use App\Http\Controllers\Owner\DashboardOwnerController;
use App\Http\Controllers\Owner\ReportOwnerController;
use App\Http\Controllers\Owner\OrderMonitoringController;

use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth','role:admin'])->prefix('admin')->group(function(){
    Route::get('/dashboard',[DashboardController::class,'index'])->name('admin.dashboard');
    Route::resource('/products',ProductController::class)->names('admin.products');
    Route::resource('colors', ColorController::class)->except(['show'])->names('admin.colors');
    Route::resource('/customers',CustomerController::class)->names('admin.customers');
    Route::resource('/orders',OrderController::class)->names('admin.orders');
    Route::match(['post', 'put'], '/orders/{order}/form_edit', [OrderController::class, 'segarkanFormEdit'])->name('admin.orders.form_edit');
    Route::put('/orders/{order}/status',[OrderController::class,'updateStatus'])->name('admin.orders.updateStatus');
    Route::post('/orders/tambah_baris', [OrderController::class, 'tambahBaris'])->name('admin.orders.tambah_baris');
    Route::post('/orders/cari_pelanggan', [OrderController::class, 'cariPelanggan'])->name('admin.orders.cari_pelanggan');
    Route::post('/orders/pilih_pelanggan', [OrderController::class, 'pilihPelanggan'])->name('admin.orders.pilih_pelanggan');
    Route::post('/orders/ganti_pelanggan', [OrderController::class, 'gantiPelanggan'])->name('admin.orders.ganti_pelanggan');
    Route::get('/admin/orders/{order}/invoice',[OrderController::class, 'invoice'])->name('admin.orders.invoice');
    Route::get('/orders/{order}/payment',[PaymentController::class,'create'])->name('admin.payments.create');
    Route::post('/orders/{order}/payment',[PaymentController::class,'store'])->name('admin.payments.store');
    Route::get('/orders/{order}/shipment',[ShipmentController::class,'create'])->name('admin.shipments.create');
    Route::post('/orders/{order}/shipment',[ShipmentController::class,'store'])->name('admin.shipments.store');
    Route::get('/reports',[ReportController::class,'index'])->name('admin.reports.index');
    Route::get('/reports/pdf',[ReportController::class,'pdf'])->name('admin.reports.pdf');
    Route::get('/reports/{order}',[ReportController::class,'show'])->name('admin.reports.show');
    Route::put('/orders/{order}/selesai',[OrderController::class,'selesai'])->name('admin.orders.selesai');
    Route::get('/payments',[PaymentController::class,'index'])->name('admin.payments.index');
    Route::get('/admin/shipments',[ShipmentController::class,'index'])->name('admin.shipments.index');
});

Route::prefix('owner')->middleware(['auth','role:owner'])->group(function(){
    Route::get('/dashboard',[DashboardOwnerController::class,'index'])->name('owner.dashboard');
    Route::get('/orders',[OrderMonitoringController::class,'index'])->name('owner.orders.index');
    Route::get('/reports',[ReportOwnerController::class,'index'])->name('owner.reports.index');
    Route::get('/reports/transaksi',[ReportOwnerController::class,'transaksi'])->name('owner.reports.transaksi');
    Route::get('/reports/transaksi/pdf',[ReportOwnerController::class,'transaksiPdf'])->name('owner.reports.transaksi.pdf');
    Route::get('/reports/piutang',[ReportOwnerController::class,'piutang'])->name('owner.reports.piutang');
    Route::get('/reports/piutang/pdf',[ReportOwnerController::class,'piutangPdf'])->name('owner.reports.piutang.pdf');
});

//Ini buat pelanggan :
Route::get('/cek-status',[TrackingController::class,'index'])->name('tracking.index');
Route::post('/cek-status',[TrackingController::class,'search'])->name('tracking.search');
Route::get('/cek-status/detail/{kode_pesanan}',[TrackingController::class,'detail'])->name('tracking.detail');

Route::post('/logout',function(){
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
    }
)->name('logout');

require __DIR__.'/auth.php';

