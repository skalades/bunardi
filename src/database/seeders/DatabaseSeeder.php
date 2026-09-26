<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        if(User::count() == 0) {
            User::create([
                'name' => 'Admin Bu Nardi',
                'email' => 'admin@bunardi.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);
            User::create([
                'name' => 'Crew Catering 1',
                'email' => 'catering@bunardi.com',
                'password' => Hash::make('password'),
                'role' => 'crew_catering',
            ]);
            User::create([
                'name' => 'Crew Decor 1',
                'email' => 'decor@bunardi.com',
                'password' => Hash::make('password'),
                'role' => 'crew_decor',
            ]);
            User::create([
                'name' => 'Crew Laundry 1',
                'email' => 'laundry@bunardi.com',
                'password' => Hash::make('password'),
                'role' => 'laundry',
            ]);
            User::create([
                'name' => 'Tukang Bangunan 1',
                'email' => 'bangunan@bunardi.com',
                'password' => Hash::make('password'),
                'role' => 'tukang_bangunan',
            ]);
        }

        // Clients
        $client1 = Client::firstOrCreate(
            ['kontak' => '081234567890'],
            ['nama' => 'PT Karya Bersama', 'alamat' => 'Jl. Sudirman No. 1, Jakarta']
        );
        $client2 = Client::firstOrCreate(
            ['kontak' => '081987654321'],
            ['nama' => 'Ibu Ratna', 'alamat' => 'Jl. Melati No. 45, Bandung']
        );

        // Items (Master Barang)
        $items = [
            ['nama' => 'Piring Makan Keramik', 'kategori' => 'Catering', 'satuan' => 'pcs'],
            ['nama' => 'Gelas Kaca', 'kategori' => 'Catering', 'satuan' => 'pcs'],
            ['nama' => 'Sendok & Garpu', 'kategori' => 'Catering', 'satuan' => 'set'],
            ['nama' => 'Pemanas Makanan (Chafing Dish)', 'kategori' => 'Catering', 'satuan' => 'unit'],
            ['nama' => 'Meja Bundar 120cm', 'kategori' => 'Decor', 'satuan' => 'unit'],
            ['nama' => 'Kursi Tiffany Gold', 'kategori' => 'Decor', 'satuan' => 'pcs'],
            ['nama' => 'Taplak Meja Putih', 'kategori' => 'Laundry', 'satuan' => 'pcs'],
            ['nama' => 'Sarung Kursi', 'kategori' => 'Laundry', 'satuan' => 'pcs'],
        ];

        foreach ($items as $itemData) {
            Item::firstOrCreate(['nama' => $itemData['nama']], $itemData);
        }

        // Orders & Menu (Paket Menu)
        $order1 = Order::firstOrCreate(
            ['client_id' => $client1->id, 'tanggal_acara' => now()->addDays(3)->toDateString()],
            [
                'lokasi' => 'Gedung Serbaguna ABC',
                'jumlah_pax' => 500,
                'paket_menu' => 'Paket Prasmanan Premium (Nasi, Sapi Lada Hitam, Ayam Rica, Es Buah)',
                'status' => 'Persiapan',
                'catatan' => 'Loading barang H-1 jam 15:00'
            ]
        );

        $order2 = Order::firstOrCreate(
            ['client_id' => $client2->id, 'tanggal_acara' => now()->addDays(5)->toDateString()],
            [
                'lokasi' => 'Rumah Kediaman Ibu Ratna',
                'jumlah_pax' => 100,
                'paket_menu' => 'Paket Gubukan (Sate, Bakso, Siomay)',
                'status' => 'Baru',
                'catatan' => 'Akses jalan sempit, pakai mobil pick up kecil'
            ]
        );

        // Order Items (Rencana Barang)
        $itemIds = Item::pluck('id', 'nama');
        
        $order1Rencana = [
            'Piring Makan Keramik' => 500,
            'Gelas Kaca' => 500,
            'Sendok & Garpu' => 500,
            'Pemanas Makanan (Chafing Dish)' => 10,
            'Meja Bundar 120cm' => 20,
            'Kursi Tiffany Gold' => 200,
            'Taplak Meja Putih' => 20,
        ];

        foreach ($order1Rencana as $nama => $qty) {
            if (isset($itemIds[$nama])) {
                OrderItem::firstOrCreate([
                    'order_id' => $order1->id,
                    'item_id' => $itemIds[$nama],
                ], ['qty_rencana' => $qty]);
            }
        }

        $order2Rencana = [
            'Piring Makan Keramik' => 100,
            'Sendok & Garpu' => 100,
        ];

        foreach ($order2Rencana as $nama => $qty) {
            if (isset($itemIds[$nama])) {
                OrderItem::firstOrCreate([
                    'order_id' => $order2->id,
                    'item_id' => $itemIds[$nama],
                ], ['qty_rencana' => $qty]);
            }
        }
    }
}
