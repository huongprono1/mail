<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\ViewRecord;

class ViewPost extends ViewRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            // Có thể thêm widget hiển thị thông tin tổng quan bài viết ở đây nếu muốn
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // Có thể thêm widget hiển thị tags hoặc SEO info ở đây nếu muốn
        ];
    }
}
