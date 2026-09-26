<?php

namespace App\Filament\Resources\InventoryTransactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InventoryTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('item_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('order_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tipe')
                    ->searchable(),
                TextColumn::make('qty')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('kondisi')
                    ->searchable(),
                TextColumn::make('alasan')
                    ->searchable(),
                TextColumn::make('pic_user_id')
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
