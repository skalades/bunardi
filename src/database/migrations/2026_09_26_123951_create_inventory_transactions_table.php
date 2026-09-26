<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->enum('tipe', ['keluar', 'masuk']);
            $table->integer('qty');
            $table->enum('kondisi', ['baik', 'rusak', 'hilang'])->default('baik');
            $table->string('alasan')->nullable();
            $table->foreignId('pic_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('waktu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
