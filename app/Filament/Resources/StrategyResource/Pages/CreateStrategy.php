<?php

namespace App\Filament\Resources\StrategyResource\Pages;

use App\Filament\Resources\StrategyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStrategy extends CreateRecord
{
    protected static string $resource = StrategyResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['account_id'] = auth()->user()->currentAccount()->id;

        return $data;
    }
}
