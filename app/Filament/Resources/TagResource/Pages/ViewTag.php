<?php

namespace App\Filament\Resources\TagResource\Pages;

use App\Filament\Resources\TagResource;
use Filament\Resources\Pages\ViewRecord;

class ViewTag extends ViewRecord
{
    protected static string $resource = TagResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            // Có thể thêm widget hiển thị tổng quan tag ở đây nếu muốn
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // Có thể thêm widget hiển thị danh sách bài viết liên quan ở đây nếu muốn
        ];
    }
}
