<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bisnis')->default('BU NARDI CATERING & N7DECORATION');
            $table->string('logo')->nullable();
            $table->text('alamat')->nullable();
            $table->string('kontak')->nullable();
            $table->text('info_rekening')->nullable();
            $table->text('catatan_invoice')->nullable();
            $table->timestamps();
        });

        // Insert default row
        DB::table('company_profiles')->insert([
            'nama_bisnis' => 'BU NARDI CATERING & N7DECORATION',
            'alamat' => 'Garut, Jawa Barat',
            'kontak' => '0812-XXXX-XXXX',
            'info_rekening' => "BCA: 1234567890 a.n Bu Nardi\nMandiri: 0987654321 a.n Bu Nardi",
            'catatan_invoice' => 'Terima kasih telah mempercayakan acara Anda kepada kami. Pembayaran DP minimal 50% sebelum hari H.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('company_profiles');
    }
};
