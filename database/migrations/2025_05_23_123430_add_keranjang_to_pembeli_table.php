<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pembeli', function (Blueprint $table) {
            // Jika kolom belum ada, buat varchar(1000), kalau sudah json atau lain bisa diubah:
            $table->string('keranjang', 1000)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pembeli', function (Blueprint $table) {
            $table->json('keranjang')->nullable()->change();
        });
    }
};

