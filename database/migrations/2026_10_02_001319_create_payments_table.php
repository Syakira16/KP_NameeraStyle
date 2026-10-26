<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            // ID pembayaran
            $table->id();

            // Pembayaran milik pesanan yang mana
            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            // Tanggal pembayaran
            $table->date('tanggal_bayar');
            // Jumlah uang yang dibayarkan
            $table->decimal('jumlah_bayar', 15, 2);

            // Metode pembayaran
            // Contoh: Transfer Bank, E-Wallet, Tunai
            $table->string('metode_bayar');

            // Bukti pembayaran
            // Menyimpan nama/path file bukti pembayaran
            $table->string('bukti_bayar')->nullable();

            // created_at dan updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
