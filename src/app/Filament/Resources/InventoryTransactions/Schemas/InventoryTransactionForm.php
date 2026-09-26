<?php

namespace App\Filament\Resources\InventoryTransactions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class InventoryTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('item_id')
                    ->relationship('item', 'nama')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Barang'),
                Select::make('order_id')
                    ->relationship('order', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Order #{$record->id} - " . ($record->client->nama ?? ''))
                    ->searchable()
                    ->preload()
                    ->label('Terkait Order (Opsional)'),
                Select::make('tipe')
                    ->options([
                        'keluar' => 'Keluar (Dibawa / Dipakai)',
                        'masuk' => 'Masuk (Kembali / Beli Baru)'
                    ])
                    ->required()
                    ->native(false)
                    ->label('Tipe Transaksi'),
                TextInput::make('qty')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->label('Jumlah (Qty)'),
                Select::make('kondisi')
                    ->options([
                        'baik' => 'Baik',
                        'rusak' => 'Rusak',
                        'hilang' => 'Hilang'
                    ])
                    ->required()
                    ->default('baik')
                    ->native(false),
                TextInput::make('alasan')
                    ->label('Catatan Khusus (Alasan Rusak/Hilang)'),
                Select::make('pic_user_id')
                    ->relationship('pic', 'name')
                    ->searchable()
                    ->preload()
                    ->label('PIC (Penanggung Jawab)'),
                DateTimePicker::make('waktu')
                    ->required()
                    ->default(now()),
            ]);
    }
}
