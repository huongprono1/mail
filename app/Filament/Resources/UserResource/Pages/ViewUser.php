<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Widgets\UserStatOverview;
use Filament\Infolists;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function infolist(Infolists\Infolist $infolist): Infolists\Infolist
    {
        return $infolist
            ->schema([
                Section::make('Thông tin tài khoản')
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('email'),
                        TextEntry::make('created_at')->dateTime()->label('Ngày tạo'),
                        TextEntry::make('updated_at')->dateTime()->label('Cập nhật'),
                        TextEntry::make('email_verified_at')->dateTime()->label('Xác thực email'),
                    ])
                    ->columns(3),
            ]);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            UserStatOverview::class,
        ];
    }
}
