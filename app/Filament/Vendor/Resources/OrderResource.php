<?php

namespace App\Filament\Vendor\Resources;

use App\Filament\Vendor\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Orders';
    protected static ?string $label = 'Order';
    protected static ?string $pluralLabel = 'Orders';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Vendors usually won’t create/edit orders manually,
            // but you could add status change, notes, etc. here if needed.
            Forms\Components\Select::make('status')
                ->label('Order Status')
                ->options([
                    'pending'   => 'Pending',
                    'accepted'  => 'Accepted',
                    'ready'     => 'Ready',
                    'delivered' => 'Delivered',
                    'cancelled' => 'Cancelled',
                ])
                ->required(),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Order #')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_price')
                    ->money('bdt', true) // 💡 change currency if needed
                    ->label('Total')
                    ->sortable(),

                Tables\Columns\TextColumn::make('delivery_charge')
                    ->money('bdt', true)
                    ->label('Delivery Charge'),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info'    => 'processing',
                        'success' => 'delivered',
                        'danger'  => 'cancelled',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('expected_receive_time')
                    ->dateTime('M d, Y H:i')
                    ->label('Expected On')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // vendors see order details
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(), // optional
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // you could add RelationManagers here (like order items)
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('vendorApplication', function ($query) {
                $query->where('user_id', auth()->id());
            });
    }
}
