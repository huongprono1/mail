<?php

namespace App\Filament\App\Pages;

use App\Models\UserPlan;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Number;

class UserPlanHistory extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.user-plan-history';

    protected static ?string $slug = 'user-plan-history';

    public ?UserPlan $currentPlan;

    public function getTitle(): string|Htmlable
    {
        return __('My Plan History');
    }

    public static function getNavigationLabel(): string
    {
        return __('My Plan History');
    }

    public function mount(): void
    {
        if (! auth()->check()) {
            redirect()->route('filament.app.auth.login');
        }

        $this->currentPlan = auth()->user()?->currentPlan;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(UserPlan::query()
                ->with('plan')
                ->where('user_id', auth()->id())
                ->orderByDesc('id')
            )
            ->columns([
                TextColumn::make('plan.name')
                    ->label('Plan')
                    ->translateLabel(),
                TextColumn::make('billing_cycle')
                    ->label('Billing Cycle')
                    ->translateLabel()
                    ->formatStateUsing(fn ($state) => __($state))
                    ->placeholder('-')
                    ->alignment('center'),
                TextColumn::make('amount')
                    ->label('Price')
                    ->translateLabel()
                    ->formatStateUsing(fn ($state, $record) => Number::currency($state, $record->currency ?? 'VND'))
                    ->alignment('center'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->translateLabel()
                    ->alignment('center'),
                TextColumn::make('started_at')
                    ->label('Started At')
                    ->translateLabel()
                    ->placeholder('-')
                    ->alignment('center'),
                TextColumn::make('expired_at')
                    ->label('Expired At')
                    ->translateLabel()
                    ->placeholder('-')
                    ->alignment('center'),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->paginated(false);
    }
}
