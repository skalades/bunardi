<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Item extends Model {
    protected $guarded = [];
    public function inventoryTransactions() { return $this->hasMany(InventoryTransaction::class); }
    public function stockCheckpoints() { return $this->hasMany(StockCheckpoint::class); }
    
    public function getEstimasiStokAttribute() {
        $lastCheckpoint = $this->stockCheckpoints()->latest('waktu')->first();
        $startQty = $lastCheckpoint ? $lastCheckpoint->qty_aktual : 0;
        
        $query = $this->inventoryTransactions();
        if ($lastCheckpoint) {
            $query->where('waktu', '>', $lastCheckpoint->waktu);
        }
        
        $masuk = (clone $query)->where('tipe', 'masuk')->sum('qty');
        $keluar = (clone $query)->where('tipe', 'keluar')->sum('qty');
        
        return $startQty + $masuk - $keluar;
    }
}
