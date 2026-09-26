<?php

namespace App\Filament\Resources\StockCheckpoints;

use App\Filament\Resources\StockCheckpoints\Pages\CreateStockCheckpoint;
use App\Filament\Resources\StockCheckpoints\Pages\EditStockCheckpoint;
use App\Filament\Resources\StockCheckpoints\Pages\ListStockCheckpoints;
use App\Filament\Resources\StockCheckpoints\Schemas\StockCheckpointForm;
use App\Filament\Resources\StockCheckpoints\Tables\StockCheckpointsTable;
use App\Models\StockCheckpoint;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StockCheckpointResource extends Resource
{
    protected static ?string $model = StockCheckpoint::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return StockCheckpointForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockCheckpointsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStockCheckpoints::route('/'),
            'create' => CreateStockCheckpoint::route('/create'),
            'edit' => EditStockCheckpoint::route('/{record}/edit'),
        ];
    }
}
