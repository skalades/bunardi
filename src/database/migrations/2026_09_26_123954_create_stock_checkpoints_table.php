<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_checkpoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->integer('qty_aktual');
            $table->integer('selisih_vs_estimasi')->default(0);
            $table->foreignId('dicatat_oleh_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('waktu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_checkpoints');
    }
};
