<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {
    protected $guarded = [];
    protected $casts = ['tanggal_terbit' => 'date', 'tanggal_lunas' => 'date'];
    public function order() { return $this->belongsTo(Order::class); }
    public function items() { return $this->hasMany(InvoiceItem::class); }
    public function payments() { return $this->hasMany(Payment::class); }
}
