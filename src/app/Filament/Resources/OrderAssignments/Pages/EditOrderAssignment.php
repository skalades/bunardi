<?php

namespace App\Filament\Resources\OrderAssignments\Pages;

use App\Filament\Resources\OrderAssignments\OrderAssignmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrderAssignment extends EditRecord
{
    protected static string $resource = OrderAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
