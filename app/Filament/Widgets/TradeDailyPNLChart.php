<?php

namespace App\Filament\Widgets;

use App\Models\Trade;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class TradeDailyPNLChart extends ChartWidget
{
    protected static ?int $sort = 1;

    protected static ?string $heading = 'Daily P/L';

    protected function getData(): array
    {
        $startDate = Carbon::now()->subDays(13);
        $endDate = Carbon::now();

        $trades = Trade::selectRaw('DATE(closes_at) as date, sum(pnl) as pnl')
            ->whereBetween('closes_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->groupBy('date')
            ->get()
            ->keyBy('date')
            ->toArray();

        $labels = [];
        $data = [];

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');
            $labels[] = $formattedDate;
            $data[] = $trades[$formattedDate]['pnl'] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'P/L',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
