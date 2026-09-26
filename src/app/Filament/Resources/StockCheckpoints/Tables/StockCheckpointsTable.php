<?php

namespace App\Filament\Resources\StockCheckpoints\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StockCheckpointsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('item_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('qty_aktual')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('selisih_vs_estimasi')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dicatat_oleh_user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('waktu')
                    ->dateTime()
                    ->sortable(),
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
