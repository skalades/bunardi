<?php

namespace App\Filament\Resources\StockCheckpoints\Pages;

use App\Filament\Resources\StockCheckpoints\StockCheckpointResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStockCheckpoint extends EditRecord
{
    protected static string $resource = StockCheckpointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
