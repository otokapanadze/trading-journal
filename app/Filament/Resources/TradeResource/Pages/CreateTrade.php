<?php

namespace App\Filament\Resources\TradeResource\Pages;

use App\Filament\Resources\TradeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTrade extends CreateRecord
{
    protected static string $resource = TradeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['account_id'] = auth()->user()->currentAccount()->id;

        return $data;
    }
}
