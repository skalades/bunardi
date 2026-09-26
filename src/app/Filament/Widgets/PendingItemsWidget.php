<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\InventoryTransaction;

class PendingItemsWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static ?string $heading = '⚠ Barang Belum Kembali (Perlu Ditindaklanjuti)';
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                InventoryTransaction::query()
                    ->select('order_id', 'item_id')
                    ->selectRaw('SUM(CASE WHEN tipe = "keluar" THEN qty ELSE 0 END) as total_keluar')
                    ->selectRaw('SUM(CASE WHEN tipe = "masuk" THEN qty ELSE 0 END) as total_masuk')
                    ->whereNotNull('order_id')
                    ->groupBy('order_id', 'item_id')
                    ->havingRaw('SUM(CASE WHEN tipe = "keluar" THEN qty ELSE 0 END) > SUM(CASE WHEN tipe = "masuk" THEN qty ELSE 0 END)')
            )
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('order.client.nama')
                    ->label('Klien / Order')
                    ->description(fn ($record) => 'Order #' . $record->order_id),
                Tables\Columns\TextColumn::make('item.nama')
                    ->label('Nama Barang'),
                Tables\Columns\TextColumn::make('belum_kembali')
                    ->label('Belum Kembali')
                    ->badge()
                    ->color('danger')
                    ->state(fn ($record) => ($record->total_keluar - $record->total_masuk) . ' ' . ($record->item->satuan ?? '')),
            ]);
    }
}
