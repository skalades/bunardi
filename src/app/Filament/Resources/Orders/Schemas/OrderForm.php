<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Support\RawJs;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Get;
use Filament\Forms\Set;

class OrderForm
{
    public static function updateGrandTotal($get, $set): void
    {
        $pax = (float) ($get('jumlah_pax') ?: 0);
        $deal = (float) ($get('harga_pax_deal') ?: 0);
        $tambahan = (float) ($get('biaya_tambahan') ?: 0);

        $set('grand_total', ($pax * $deal) + $tambahan);
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Tabs::make('Tabs')
                    ->tabs([
                        \Filament\Schemas\Components\Tabs\Tab::make('Informasi Umum')
                            ->schema([
                                \Filament\Forms\Components\Select::make('client_id')
                                    ->relationship('client', 'nama')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        \Filament\Forms\Components\TextInput::make('nama')
                                            ->required(),
                                        \Filament\Forms\Components\TextInput::make('kontak'),
                                        \Filament\Forms\Components\Textarea::make('alamat'),
                                    ])
                                    ->required(),
                                DatePicker::make('tanggal_acara')
                                    ->required(),
                                TextInput::make('lokasi')
                                    ->required(),
                                TextInput::make('jumlah_pax')
                                    ->required()
                                    ->numeric()
                                    ->default(0)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($get, $set) => self::updateGrandTotal($get, $set)),
                                \Filament\Forms\Components\Select::make('status')
                                    ->required()
                                    ->options([
                                        'Baru' => 'Baru',
                                        'Persiapan' => 'Persiapan',
                                        'Sedang Berjalan' => 'Sedang Berjalan',
                                        'Selesai' => 'Selesai',
                                        'Batal' => 'Batal',
                                    ])
                                    ->default('Baru'),
                                Textarea::make('catatan')
                                    ->columnSpanFull(),
                            ])->columns(2),
                        \Filament\Schemas\Components\Tabs\Tab::make('Detail Paket & Harga')
                            ->schema([
                                TextInput::make('paket_menu'),
                                TextInput::make('harga_pax_normal')
                                    ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                                    ->stripCharacters('.')
                                    ->numeric()
                                    ->prefix('Rp'),
                                TextInput::make('harga_pax_deal')
                                    ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                                    ->stripCharacters('.')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($get, $set) => self::updateGrandTotal($get, $set)),
                                TextInput::make('biaya_tambahan')
                                    ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                                    ->stripCharacters('.')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($get, $set) => self::updateGrandTotal($get, $set)),
                                TextInput::make('grand_total')
                                    ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                                    ->stripCharacters('.')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly(),
                            ])->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
