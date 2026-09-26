<?php

namespace App\Filament\Resources\StockCheckpoints\Pages;

use App\Filament\Resources\StockCheckpoints\StockCheckpointResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStockCheckpoints extends ListRecords
{
    protected static string $resource = StockCheckpointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
