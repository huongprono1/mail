<?php

namespace App\Filament\Widgets;

use App\Models\Mail;
use App\Models\Message;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class MailAndMessage extends ChartWidget
{
    protected static ?string $heading = 'Mails and Message';

    protected static string $color = 'primary';

    protected static ?int $sort = 2;

    public ?string $filter = 'last_7_days';

    public ?Carbon $startDate = null;

    public ?Carbon $endDate = null;

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

        $mailTrend = Trend::model(Mail::class)
            ->between(
                start: $startDate,
                end: $endDate
            )
            ->perDay()
            ->count();

        $messageTrend = Trend::model(Message::class)
            ->between(
                start: $startDate,
                end: $endDate
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Mails',
                    'data' => $mailTrend->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#FF5733',
                    'backgroundColor' => 'rgba(255, 87, 51, 0.5)',
                ],
                [
                    'label' => 'Messages',
                    'data' => $messageTrend->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#33A1FF',
                    'backgroundColor' => 'rgba(51, 161, 255, 0.5)',
                ],
            ],
            'labels' => $mailTrend->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return $this->filter == 'today' ? 'bar' : 'line';
    }
}
