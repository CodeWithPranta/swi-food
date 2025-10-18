<?php

namespace App\Filament\Vendor\Resources;

use App\Filament\Vendor\Resources\DeliveryChargeResource\Pages;
use App\Filament\Vendor\Resources\DeliveryChargeResource\RelationManagers;
use App\Models\DeliveryCharge;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\DeliveryArea;
use Illuminate\Support\Facades\Auth;

class DeliveryChargeResource extends Resource
{
    protected static ?string $model = DeliveryCharge::class;

    protected static ?string $navigationIcon = 'heroicon-o-plus';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id()); // Only show logged-in vendor's application
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => auth()->id())
                    ->required(),
                Forms\Components\TextInput::make('area')
                    ->label('Delivery Area')
                    ->required()
                    ->minLength(3)
                    ->maxLength(255),
                Forms\Components\TextInput::make('charge')
                    ->prefix('CHF')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('area')
                    ->searchable(),
                Tables\Columns\TextColumn::make('charge')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListDeliveryCharges::route('/'),
            'create' => Pages\CreateDeliveryCharge::route('/create'),
            'edit' => Pages\EditDeliveryCharge::route('/{record}/edit'),
        ];
    }
}
