<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->decimal('harga', 15, 2)->default(0)->after('satuan');
            $table->string('gambar')->nullable()->after('harga');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('harga', 15, 2)->default(0)->after('qty_rencana');
            $table->decimal('subtotal', 15, 2)->default(0)->after('harga');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['harga', 'subtotal']);
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['harga', 'gambar']);
        });
    }
};
