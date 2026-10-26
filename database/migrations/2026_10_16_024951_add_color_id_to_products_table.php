<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan color_id ke tabel products
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->foreignId('color_id')
                ->nullable()
                ->after('harga')
                ->constrained('colors')
                ->nullOnDelete();

        });
    }

    /**
     * Menghapus color_id jika migration dibatalkan
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->dropForeign(['color_id']);
            $table->dropColumn('color_id');

        });
    }
};