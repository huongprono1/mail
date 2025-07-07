<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ClientAndUser extends ChartWidget
{
    protected static ?string $heading = 'Clients and Users';

    public ?string $filter = 'last_30_days';

    protected static ?int $sort = 1;

    protected function getFilters(): array
    {
        return [
            'today' => 'Hôm nay',
            'last_7_days' => '7 ngày qua',
            'last_30_days' => '30 ngày qua',
        ];
    }

    protected function getData(): array
    {
        $filter = $this->filter; // Lấy filter từ Filament

        $startDate = match ($filter) {
            'today' => now()->startOfDay(),
            'last_7_days' => now()->subDays(7),
            'last_30_days' => now()->subDays(30),
            default => request('start_date', now()->subDays(30)), // Custom
        };

        $endDate = request('end_date', now()); // Lấy ngày kết thúc nếu chọn custom

        // Thống kê Clients
        $clientTrend = Trend::query(Client::query())
            ->between(start: $startDate, end: $endDate)
            ->perDay()
            ->count();

        // Thống kê Users
        $userTrend = Trend::query(User::query())
            ->between(start: $startDate, end: $endDate)
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Clients',
                    'data' => $clientTrend->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => '#FF5733',
                    'backgroundColor' => 'rgba(255, 87, 51, 0.5)',
                ],
                [
                    'label' => 'Users',
                    'data' => $userTrend->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => '#33A1FF',
                    'backgroundColor' => 'rgba(51, 161, 255, 0.5)',
                ],
            ],
            'labels' => $clientTrend->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
