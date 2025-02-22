<?php

namespace App\Filament\Resources\TradeResource\Pages;

use App\Filament\Resources\TradeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrade extends EditRecord
{
    protected static string $resource = TradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->url(route('filament.app.resources.trades.create')),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
