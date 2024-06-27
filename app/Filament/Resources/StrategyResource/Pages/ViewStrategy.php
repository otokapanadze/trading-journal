<?php

namespace App\Filament\Resources\StrategyResource\Pages;

use App\Filament\Resources\StrategyResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStrategy extends ViewRecord
{
    protected static string $resource = StrategyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
