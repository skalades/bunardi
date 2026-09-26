<?php

namespace App\Filament\Resources\OrderAssignments\Pages;

use App\Filament\Resources\OrderAssignments\OrderAssignmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderAssignment extends CreateRecord
{
    protected static string $resource = OrderAssignmentResource::class;
}
