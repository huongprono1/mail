<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;

class ClientBrowserOverview extends ChartWidget
{
    protected static ?string $heading = 'Client Browsers Overview';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $startDate = now()->subDays(30);
        $endDate = now();

        $browsers = Client::query()->whereDate('created_at', '>=', $startDate)
            ->selectRaw('browser, COUNT(*) as count')
            ->groupBy('browser')
            ->orderBy('count', 'desc')
            ->get();
        //        $devices = Client::query()->whereDate('created_at', '>=', $startDate)
        //            ->selectRaw('device, COUNT(*) as count')
        //            ->groupBy('device')
        //            ->orderBy('count', 'desc')
        //            ->get();

        $deviceTrend = Trend::model(Client::class)
            ->between(
                start: $startDate,
                end: $endDate
            )
            ->perDay()
            ->count('device');

        $platformTrend = Trend::model(Client::class)
            ->between(
                start: $startDate,
                end: $endDate
            )
            ->perDay()
            ->count('platform');

        return [
            'datasets' => [
                [
                    'label' => 'Browser',
                    'data' => $browsers->pluck('count')->toArray(),
                    'backgroundColor' => $browsers->map(fn($row) => '#' . substr(md5($row->browser), 0, 6))->toArray(),
                ],
                //                [
                //                    'label' => 'Devices',
                //                    'data' => $devices->pluck('count')->toArray(),
                //                    'backgroundColor' => $devices->map(fn($row) => '#' . substr(md5($row->device), 0, 6))->toArray(),
                //                ],
            ],
            'labels' => $browsers->pluck('browser')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
