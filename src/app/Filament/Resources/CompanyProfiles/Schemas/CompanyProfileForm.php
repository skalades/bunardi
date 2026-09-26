<?php

namespace App\Filament\Resources\CompanyProfiles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CompanyProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_bisnis')
                    ->required()
                    ->default('BU NARDI CATERING & N7DECORATION'),
                \Filament\Forms\Components\FileUpload::make('logo')->image(),
                Textarea::make('alamat')
                    ->columnSpanFull(),
                TextInput::make('kontak'),
                Textarea::make('info_rekening')
                    ->columnSpanFull(),
                Textarea::make('catatan_invoice')
                    ->columnSpanFull(),
            ]);
    }
}
