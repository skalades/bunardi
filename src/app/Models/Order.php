<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $guarded = [];
    protected $casts = ['tanggal_acara' => 'date'];
    public function client() { return $this->belongsTo(Client::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
    public function inventoryTransactions() { return $this->hasMany(InventoryTransaction::class); }
    public function invoice() { return $this->hasOne(Invoice::class); }
    public function assignments() { return $this->hasMany(OrderAssignment::class); }
    public function payments() { return $this->hasManyThrough(Payment::class, Invoice::class); }
}
