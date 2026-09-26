<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StockCheckpoint extends Model {
    protected $guarded = [];
    protected $casts = ['waktu' => 'datetime'];
    public function item() { return $this->belongsTo(Item::class); }
    public function dicatatOleh() { return $this->belongsTo(User::class, 'dicatat_oleh_user_id'); }
}
