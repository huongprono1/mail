<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use Filament\Widgets\ChartWidget;

class ClientDeviceOverview extends ChartWidget
{
    protected static ?string $heading = 'Client Devices Overview';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $startDate = now()->subDays(30);

        $devices = Client::query()->whereDate('created_at', '>=', $startDate)
            ->selectRaw('device, COUNT(*) as count')
            ->groupBy('device')
            ->orderBy('count', 'desc')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Device',
                    'data' => $devices->pluck('count')->toArray(),
                    'backgroundColor' => $devices->map(fn($row) => '#' . substr(md5($row->device), 0, 6))->toArray(),
                ],
            ],
            'labels' => $devices->pluck('device')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
