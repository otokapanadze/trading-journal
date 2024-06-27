<?php

namespace App\Filament\Widgets;

use App\Models\Strategy;
use App\Models\Trade;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class StrategyTopPNLChart extends ChartWidget
{
    protected static ?int $sort = 4;

    protected static ?string $heading = 'Top Strategy';

    protected function getData(): array
    {
        $res = Strategy::withCount(['trades as total_pnl' => fn($q) => $q->select(DB::raw("SUM(pnl) as total_pnl"))])->get();

        return [
            'datasets' => [
                [
                    'data' => $res->pluck('total_pnl'),
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                ],
            ],
            'labels' => $res->pluck('name'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
