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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pesanan')->unique();
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            $table->date('tanggal_pesan');

            $table->decimal('total_harga', 15, 2)
                ->default(0);
            
            $table->integer('diskon')->default(0);

            $table->enum('status_pesanan', [
                'Menunggu Produksi',
                'Ready',
                'Packing',
                'Dikirim',
                'Selesai'
            ])->default('Menunggu Produksi');

            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
