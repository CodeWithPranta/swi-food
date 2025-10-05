<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Food;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\FoodResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FoodResource\RelationManagers;

class FoodResource extends Resource
{
    protected static ?string $model = Food::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Kitchen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Assign Vendor')
                    ->relationship('user', 'email', fn ($query) => $query->where('user_type', 2))
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Toggle::make('is_visible')
                    ->label('Visibility')
                    ->helperText('Whether or not the food is visible to customers.')
                    ->default(true),
                Fieldset::make('Category')
                    ->schema(
                        Category::whereNull('parent_id') // Get parent categories
                            ->with('children') // Load sub-categories
                            ->get()
                            ->map(function ($parentCategory) {
                                return Radio::make('category_id')
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
                FileUpload::make('images')
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
                RichEditor::make('description')->required()->columnSpanFull(),
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
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Select::make('unit_id')->relationship('unit', 'name')->required(),
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
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('user.email')->searchable()->sortable(),
                TextColumn::make('category.name')->searchable()->sortable(),
                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Visibility')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('price')->sortable(),
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
}
