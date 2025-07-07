<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationGroup = 'Blog';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Bài viết';

    protected static ?string $slug = 'posts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->required()->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, callable $set) {
                        $set('slug', Str::slug($state));
                    }),
                TextInput::make('meta_title')->label('Meta Title')->nullable(),
                TextInput::make('slug')->required()->unique(ignoreRecord: true)->columnSpanFull(),
                Textarea::make('meta_description')->label('Meta Description')->nullable(),
                Textarea::make('meta_keywords')->label('Meta Keywords')->nullable(),
                MarkdownEditor::make('content')->required()->columnSpanFull(),
                //                FileUpload::make('cover_image')
                //                    ->label('Ảnh bìa')
                //                    ->directory('blog-images')
                //                    ->nullable()->columnSpanFull(),
                CuratorPicker::make('cover_image')
                    ->label('Image')
                    ->relationship('image', 'id')
                    ->size('sm'),
                Select::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->createOptionForm(
                        fn (Form $form): Form => $form
                            ->schema([
                                TextInput::make('name')->required()->maxLength(255),
                                TextInput::make('slug')->required()->unique(ignoreRecord: true),
                            ])
                    ),
                DateTimePicker::make('published_at')
                    ->default(now())
                    ->label('Ngày xuất bản')
                    ->nullable(),
                Select::make('user_id')
                    ->relationship('user', 'name')->searchable(['name', 'email'])->default(auth()->id())->required(),
                Toggle::make('is_published')
                    ->label('Xuất bản'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('slug')->searchable(),
                TextColumn::make('user.name')->label('Tác giả')->sortable(),
                IconColumn::make('is_published')->boolean()->label('Xuất bản'),
                TextColumn::make('published_at')->dateTime()->label('Ngày xuất bản'),
                TextColumn::make('tags.name')->label('Tags')->badge()->separator(','),
                TextColumn::make('created_at')->dateTime()->label('Tạo lúc'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->filters([
                // Có thể thêm filter theo tag nếu cần
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
