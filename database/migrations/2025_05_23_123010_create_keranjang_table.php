<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('keranjang', function (Blueprint $table) {
            $table->id();
            $table->string('pembeli_id'); // FK ke tabel pembeli
            $table->unsignedBigInteger('barang_id'); // FK ke tabel barang
            $table->integer('quantity')->default(1);
            $table->timestamps();

            $table->foreign('pembeli_id')->references('id')->on('pembeli')->onDelete('cascade');
            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keranjang');
    }
};

