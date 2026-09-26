<?php

namespace App\Filament\Resources\StockCheckpoints\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class StockCheckpointForm
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
                TextInput::make('qty_aktual')
                    ->required()
                    ->numeric()
                    ->label('Jumlah Fisik Aktual (Hasil Hitung)'),
                TextInput::make('selisih_vs_estimasi')
                    ->numeric()
                    ->default(0)
                    ->label('Selisih (vs Estimasi Sistem)'),
                Select::make('dicatat_oleh_user_id')
                    ->relationship('dicatatOleh', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Dicatat Oleh (PIC)'),
                DateTimePicker::make('waktu')
                    ->required()
                    ->default(now()),
            ]);
    }
}
