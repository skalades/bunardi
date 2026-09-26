<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    protected static function booted()
    {
        $syncStatus = function (Payment $payment) {
            $invoice = $payment->invoice;
            if ($invoice && $invoice->order) {
                $order = $invoice->order;
                $totalPaid = $invoice->payments()->sum('nominal');
                
                if ($totalPaid >= $order->grand_total && $order->grand_total > 0) {
                    $order->update(['status' => 'Lunas']);
                    $invoice->update(['status' => 'lunas', 'tanggal_lunas' => now()]);
                } else {
                    if ($order->status === 'Lunas') {
                        $order->update(['status' => 'Diproses']); // Or keep original status
                        $invoice->update(['status' => 'draft', 'tanggal_lunas' => null]);
                    }
                }
            }
        };

        static::saved($syncStatus);
        static::deleted($syncStatus);
    }
}
