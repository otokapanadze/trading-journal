<?php

namespace App\Filament\Resources\TradeResource\Pages;

use App\Filament\Resources\TradeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTrade extends ViewRecord
{
    protected static string $resource = TradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->url(route('filament.app.resources.trades.create')),
            Actions\EditAction::make(),
        ];
    }
}
