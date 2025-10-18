<?php

namespace App\Filament\Vendor\Resources\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $recordTitleAttribute = 'food.name'; // optional

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('food.name')
                    ->label('Food Item')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity'),
                Tables\Columns\TextColumn::make('food.price')
                    ->money('chf', true)
                    ->label('Price'),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->getStateUsing(fn($record) => ($record->food->price ?? 0) * ($record->quantity ?? 1))
                    ->money('chf', true),
                Tables\Columns\TextColumn::make('order.user.name')
                    ->label('Customer Name'),
                Tables\Columns\TextColumn::make('preference')
                    ->label('Preferences')
                    ->wrap(),
            ])
            ->headerActions([]) // remove create
            ->actions([])       // remove edit/delete
            ->bulkActions([]);  // remove bulk actions
    }
}
