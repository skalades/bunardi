<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AssignmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'assignments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->label('Karyawan / Kru'),
                \Filament\Forms\Components\Select::make('divisi')
                    ->options([
                        'Crew Catering' => 'Crew Catering',
                        'Crew Decor' => 'Crew Decor',
                        'Laundry' => 'Laundry',
                        'Tukang Bangunan' => 'Tukang Bangunan',
                        'Supir/Logistik' => 'Supir/Logistik',
                    ])
                    ->required(),
                \Filament\Forms\Components\Toggle::make('is_pic')
                    ->label('Jadikan PIC Utama')
                    ->default(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.name')
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Kru')
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('divisi')
                    ->searchable(),
                \Filament\Tables\Columns\IconColumn::make('is_pic')
                    ->boolean()
                    ->label('Status PIC'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
