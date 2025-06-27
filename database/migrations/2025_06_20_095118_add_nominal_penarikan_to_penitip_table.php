<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penitip', function (Blueprint $table) {
          
            $table->decimal('nominal_penarikan', 15, 2)->nullable()->after('saldo_penitip');
        });
    }

    public function down(): void
    {
        Schema::table('penitip', function (Blueprint $table) {
         
            $table->dropColumn('nominal_penarikan');
        });
    }
};