<?php

namespace App\Filament\Resources\OrderAssignments\Pages;

use App\Filament\Resources\OrderAssignments\OrderAssignmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrderAssignments extends ListRecords
{
    protected static string $resource = OrderAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
