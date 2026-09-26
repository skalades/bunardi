<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('cetak_invoice')
                ->label('Cetak Invoice')
                ->icon('heroicon-o-printer')
                ->color('primary')
                ->url(fn (\App\Models\Order $record) => route('order.invoice.pdf', $record))
                ->openUrlInNewTab(),
            \Filament\Actions\Action::make('cetak_spk')
                ->label('Cetak SPK')
                ->icon('heroicon-o-clipboard-document-list')
                ->color('warning')
                ->url(fn (\App\Models\Order $record) => route('order.spk.pdf', $record))
                ->openUrlInNewTab(),
            DeleteAction::make(),
        ];
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    public function getContentTabLabel(): ?string
    {
        return 'Detail Order';
    }
}
