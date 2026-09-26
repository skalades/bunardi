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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('harga_pax_normal', 15, 2)->nullable();
            $table->decimal('harga_pax_deal', 15, 2)->nullable();
            $table->decimal('biaya_tambahan', 15, 2)->nullable();
            $table->decimal('grand_total', 15, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['harga_pax_normal', 'harga_pax_deal', 'biaya_tambahan', 'grand_total']);
        });
    }
};
