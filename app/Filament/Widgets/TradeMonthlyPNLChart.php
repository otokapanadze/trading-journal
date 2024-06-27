<?php

namespace App\Filament\Widgets;

use App\Models\Trade;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TradeMonthlyPNLChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected static ?string $heading = 'Monthly P/L';

    protected function getData(): array
    {
        $startDate = Carbon::now()->subMonths(11);
        $endDate = Carbon::now();

        $trades = Trade::selectRaw("strftime('%m', closes_at) as month, strftime('%Y', closes_at) as year, sum(pnl) as pnl")
            ->whereBetween('closes_at', [$startDate->startOfMonth(), $endDate->endOfMonth()])
            ->groupBy(DB::raw("strftime('%Y', closes_at)"), DB::raw("strftime('%m', closes_at)"))
            ->get()
            ->keyBy(function ($item) {
                return Carbon::create($item->year, $item->month, 1)->format('M');
            })
            ->toArray();

        $labels = [];
        $data = [];

        for ($date = $startDate; $date->lte($endDate); $date->addMonth()) {
            $formattedDate = $date->format('M');
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
