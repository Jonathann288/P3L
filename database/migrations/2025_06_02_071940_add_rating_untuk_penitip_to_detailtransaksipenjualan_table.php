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
        Schema::table('detailtransaksipenjualan', function (Blueprint $table) {
            $table->integer('rating_untuk_penitip')->nullable()->after('total_harga'); // Menambahkan kolom baru setelah kolom 'total_harga' (opsional)
            // Anda bisa menggunakan tipe lain seperti ->tinyInteger('rating_untuk_penitip') jika rating hanya 1-5
            // ->unsignedTinyInteger('rating_untuk_penitip') juga bisa
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detailtransaksipenjualan', function (Blueprint $table) {
            $table->dropColumn('rating_untuk_penitip'); // Untuk menghapus kolom jika migrasi di-rollback
        });
    }
};