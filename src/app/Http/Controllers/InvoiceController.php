<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function generatePdf(Order $order)
    {
        // Pastikan order memiliki invoice, jika tidak, kita buatkan
        $invoice = $order->invoice;
        if (!$invoice) {
            $invoice = $order->invoice()->create([
                'status' => 'draft',
                'total' => $order->grand_total ?? 0,
                'tanggal_terbit' => now(),
            ]);
        }

        $pdf = Pdf::loadView('invoices.pdf', compact('order', 'invoice'));
        
        return $pdf->stream('Invoice-' . str_pad($order->id, 5, '0', STR_PAD_LEFT) . '.pdf');
    }

    public function generateSpkPdf(Order $order)
    {
        $pdf = Pdf::loadView('orders.spk_pdf', compact('order'));
        return $pdf->stream('SPK-' . str_pad($order->id, 5, '0', STR_PAD_LEFT) . '.pdf');
    }
}
