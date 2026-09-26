<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Invoice;
use App\Models\InventoryTransaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Order Aktif', Order::whereIn('status', ['Baru', 'Dikonfirmasi', 'Persiapan', 'Berlangsung'])->count())
                ->description('Order yang belum selesai')
                ->color('primary'),
                
            Stat::make('Invoice Belum Lunas', Invoice::where('status', 'draft')->orWhere('status', 'terkirim')->count())
                ->description('Menunggu pembayaran klien')
                ->color('warning'),
                
            Stat::make('Order Selesai', Order::where('status', 'Selesai')->count())
                ->description('Total order yang sudah selesai')
                ->color('success'),
        ];
    }
}
