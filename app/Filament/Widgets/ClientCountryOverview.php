<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use Filament\Widgets\ChartWidget;

class ClientCountryOverview extends ChartWidget
{
    protected static ?string $heading = 'Client Countries Overview';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = Client::selectRaw('country, COUNT(*) as count')
            ->groupBy('country')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Country',
                    'data' => $data->pluck('count')->toArray(),
                ],
            ],
            'labels' => $data->pluck('country')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
