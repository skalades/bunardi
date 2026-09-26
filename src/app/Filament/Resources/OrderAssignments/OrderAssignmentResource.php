<?php

namespace App\Filament\Resources\OrderAssignments;

use App\Filament\Resources\OrderAssignments\Pages\CreateOrderAssignment;
use App\Filament\Resources\OrderAssignments\Pages\EditOrderAssignment;
use App\Filament\Resources\OrderAssignments\Pages\ListOrderAssignments;
use App\Filament\Resources\OrderAssignments\Schemas\OrderAssignmentForm;
use App\Filament\Resources\OrderAssignments\Tables\OrderAssignmentsTable;
use App\Models\OrderAssignment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrderAssignmentResource extends Resource
{
    protected static ?string $model = OrderAssignment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return OrderAssignmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrderAssignmentsTable::configure($table);
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
            'index' => ListOrderAssignments::route('/'),
            'create' => CreateOrderAssignment::route('/create'),
            'edit' => EditOrderAssignment::route('/{record}/edit'),
        ];
    }
}
