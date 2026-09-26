<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model {
    protected $guarded = [];
    protected $casts = ['waktu' => 'datetime'];
    public function item() { return $this->belongsTo(Item::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function pic() { return $this->belongsTo(User::class, 'pic_user_id'); }
}
