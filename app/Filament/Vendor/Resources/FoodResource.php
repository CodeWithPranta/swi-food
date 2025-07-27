<?php

namespace App\Filament\Vendor\Resources;

use App\Filament\Vendor\Resources\FoodResource\Pages;
use App\Filament\Vendor\Resources\FoodResource\RelationManagers;
use App\Models\Food;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class FoodResource extends Resource
{
    protected static ?string $model = Food::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->default(Auth::id())
                    ->readOnly()
                    ->required(),
                Forms\Components\Toggle::make('is_visible')
                    ->label('Visibility')
                    ->helperText('Whether or not the food is visible to customers.')
                    ->default(true),
                Forms\Components\Fieldset::make('Category')
                    ->schema(
                        Category::whereNull('parent_id') // Get parent categories
                            ->with('children') // Load sub-categories
                            ->get()
                            ->map(function ($parentCategory) {
                                return Forms\Components\Radio::make('category_id')
                                    ->label($parentCategory->name) // Show parent category as label
                                    ->options(
                                        $parentCategory->children->pluck('name', 'id')->toArray()
                                    ) // Only sub-categories are selectable
                                    ->inline()
                                    ->required(); // Ensure selection is required
                            })
                            ->toArray()
                    ),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->autofocus()
                    ->afterStateUpdated(function (string $state, Set $set) {
                        $set('slug', Str::slug($state));
                    }),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\FileUpload::make('images')
                    ->image()
                    ->imageEditor()
                    ->multiple()
                    ->label('Photos')
                    ->required()
                    ->columnSpanFull()
                    ->imageEditorAspectRatios([
                        null,
                        '16:9',
                        '4:3',
                        '1:1',
                    ]),
                Forms\Components\RichEditor::make('description')->required()->columnSpanFull(),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->prefix('CHF')
                    ->required(),
                Forms\Components\TextInput::make('discount')
                    ->numeric()->required()->label('Discount (%)')->default(0),
                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(100)
                    ->default(1)
                    ->required(),
                Forms\Components\Select::make('unit_id')->relationship('unit', 'name')->required(),
                Forms\Components\TextInput::make('production_cost')
                    ->numeric()
                    ->prefix('CHF')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('user.email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category.name')->searchable()->sortable(),
                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Visibility')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFood::route('/'),
            'create' => Pages\CreateFood::route('/create'),
            'edit' => Pages\EditFood::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
              ->where('user_id', Auth::id());
    }
}
