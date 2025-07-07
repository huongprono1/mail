<?php

namespace App\Filament\Resources\PlanResource\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;

class FeaturesRelationManager extends RelationManager
{
    protected static string $relationship = 'planFeatures';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('feature_id')
                ->relationship('feature', 'name')
//                ->unique(column:['plan_id', 'unique_id'], ignoreRecord: true)
                ->rules(function (RelationManager $livewire) {
                    $recordId = $livewire->getMountedTableActionRecord()?->id;

                    return [
                        Rule::unique('plan_features', 'feature_id')
                            ->where('plan_id', $livewire->ownerRecord->id)
                            ->ignore($recordId),
                    ];
                })
                ->preload()
                ->required(),
            TextInput::make('value')->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('feature.key')->label('Key')->copyable()->badge()->color('gray'),
                Tables\Columns\TextColumn::make('feature.name')->label('Name'),
                Tables\Columns\TextColumn::make('feature.description')->label('Description'),
                Tables\Columns\TextColumn::make('value')->label('Value'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->paginated(false);
    }
}
