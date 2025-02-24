<?php

namespace App\Filament\Widgets;

use App\Models\Strategy;
use App\Models\Trade;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StrategyTopPNLMonthlyChart extends ChartWidget
{
    protected string|int|array $columnSpan = 'full';

    protected static ?int $sort = 5;

    protected static ?string $heading = 'Top Strategy Monthly P/L';

    protected function getData(): array
    {
        $startDate = Carbon::now()->subMonths(11);
        $endDate = Carbon::now();
        $labels = [];
        $datasets = [];
        $colors = [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(153, 102, 255)',
            'rgb(255, 159, 64)',
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(153, 102, 255)',
            'rgb(255, 159, 64)',
        ];

        for ($date = clone $startDate; $date->lte($endDate); $date->addMonth()) {
            $formattedDate = $date->format('M');
            $labels[] = $formattedDate;
        }

        foreach (Strategy::get() as $i => $strategy) {
            $data = [];

            $trades = Trade::selectRaw("DATE_FORMAT(closes_at, '%m') as month, DATE_FORMAT(closes_at, '%Y') as year, SUM(pnl) as pnl")
                ->whereHas('strategies', function ($query) use ($strategy) {
                    $query->where('strategies.id', $strategy->id);
                })
                ->where('account_id', Auth::user()->current_account_id)
                ->whereBetween('closes_at', [$startDate->startOfMonth(), $endDate->endOfMonth()])
                ->groupBy(DB::raw("DATE_FORMAT(closes_at, '%Y')"), DB::raw("DATE_FORMAT(closes_at, '%m')"))
                ->get()
                ->keyBy(function ($item) {
                    return Carbon::create($item->year, $item->month, 1)->format('M');
                })
                ->toArray();

            for ($date = clone $startDate; $date->lte($endDate); $date->addMonth()) {
                $formattedDate = $date->format('M');
                $data[] = $trades[$formattedDate]['pnl'] ?? 0;
            }

            $datasets[] = [
                'label' => $strategy->name,
                'data' => $data,
                'backgroundColor' => [
                    $colors[$i % count($colors)]
                ],
                'borderColor' => [
                    $colors[$i % count($colors)]
                ],
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
