<?php

namespace App\Filament\Resources\Items\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('kategori')
                    ->searchable(),
                TextColumn::make('satuan')
                    ->searchable(),
                TextColumn::make('estimasi_stok')
                    ->label('Stok Saat Ini')
                    ->badge()
                    ->color(fn ($state) => $state <= 0 ? 'danger' : 'success'),
                TextColumn::make('akurasi_stok')
                    ->label('Akurasi Stok')
                    ->state(function ($record) {
                        $lastOpname = $record->stockCheckpoints()->latest('waktu')->first();
                        return $lastOpname ? 'Terverifikasi ' . $lastOpname->waktu->format('d/m/Y') : 'Belum Opname';
                    })
                    ->badge()
                    ->color(fn ($record) => $record->stockCheckpoints()->exists() ? 'success' : 'warning'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
