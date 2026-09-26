<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_id')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->default(0),
                DatePicker::make('tanggal_terbit'),
                DatePicker::make('tanggal_lunas'),
            ]);
    }
}
