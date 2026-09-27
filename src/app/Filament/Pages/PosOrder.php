<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Item;
use App\Models\Client;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class PosOrder extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'POS Order';
    protected static string | \UnitEnum | null $navigationGroup = 'Orders';
    
    protected ?string $heading = 'POS Order';
    protected string $view = 'filament.pages.pos-order';

    public $clients;
    public $clientId;
    public $tanggalAcara;
    public $lokasi;
    public $items;
    public $cart = [];
    public $activeTab = 'Catering';

    public function mount()
    {
        $this->clients = Client::all();
        $this->loadItems();
    }

    public function loadItems()
    {
        if ($this->activeTab == 'Catering') {
            $this->items = Item::where('kategori', 'Paket Catering')->get();
        } else {
            $this->items = Item::where('kategori', 'Sewa Peralatan')->get();
        }
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->loadItems();
    }

    public function addToCart($itemId)
    {
        $item = Item::find($itemId);
        if (!$item) return;

        if (isset($this->cart[$itemId])) {
            $this->cart[$itemId]['qty']++;
        } else {
            $this->cart[$itemId] = [
                'id' => $item->id,
                'nama' => $item->nama,
                'harga' => $item->harga,
                'qty' => 1,
            ];
        }
    }

    public function updateQty($itemId, $qty)
    {
        if ($qty <= 0) {
            unset($this->cart[$itemId]);
        } else {
            $this->cart[$itemId]['qty'] = $qty;
        }
    }

    public function removeFromCart($itemId)
    {
        unset($this->cart[$itemId]);
    }

    public function getSubtotalProperty()
    {
        $total = 0;
        foreach ($this->cart as $item) {
            $total += $item['harga'] * $item['qty'];
        }
        return $total;
    }

    public function saveOrder()
    {
        $this->validate([
            'clientId' => 'required',
            'tanggalAcara' => 'required|date',
            'lokasi' => 'required',
        ]);

        if (empty($this->cart)) {
            Notification::make()->title('Keranjang Kosong')->danger()->send();
            return;
        }

        DB::transaction(function () {
            $order = Order::create([
                'client_id' => $this->clientId,
                'tanggal_acara' => $this->tanggalAcara,
                'lokasi' => $this->lokasi,
                'status' => 'Baru',
                'grand_total' => $this->subtotal,
            ]);

            foreach ($this->cart as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $cartItem['id'],
                    'qty_rencana' => $cartItem['qty'],
                    'harga' => $cartItem['harga'],
                    'subtotal' => $cartItem['harga'] * $cartItem['qty'],
                ]);
            }
        });

        $this->cart = [];
        $this->clientId = null;
        $this->tanggalAcara = null;
        $this->lokasi = null;

        Notification::make()->title('Order Berhasil Dibuat')->success()->send();
    }
}
