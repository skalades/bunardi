<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use App\Models\Payment;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Support\RawJs;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';
    
    protected static ?string $title = 'Histori Pembayaran';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('nominal')
                    ->required()
                    ->numeric()
                    ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                    ->stripCharacters('.')
                    ->prefix('Rp'),
                Forms\Components\Select::make('tipe')
                    ->options([
                        'DP 1' => 'DP 1',
                        'DP 2' => 'DP 2',
                        'DP 3' => 'DP 3',
                        'Pelunasan' => 'Pelunasan',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->required(),
                Forms\Components\Select::make('metode_bayar')
                    ->options([
                        'Cash' => 'Cash',
                        'Transfer BCA' => 'Transfer BCA',
                        'Transfer Mandiri' => 'Transfer Mandiri',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('tanggal')
                    ->required()
                    ->default(now()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tipe')
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipe')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nominal')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('metode_bayar')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()
                    ->using(function (array $data, string $model): Model {
                        $order = $this->getOwnerRecord();
                        
                        // Buat invoice jika belum ada
                        $invoice = $order->invoice ?? $order->invoice()->create([
                            'status' => 'draft',
                            'total' => $order->grand_total ?? 0,
                            'tanggal_terbit' => now(),
                        ]);
                        
                        return $invoice->payments()->create($data);
                    }),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
