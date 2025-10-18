<?php

namespace App\Filament\Vendor\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $vendor = Auth::user();

        // Pending Orders
        $pendingOrders = Order::whereHas('items.food', function ($query) use ($vendor) {
            $query->where('user_id', $vendor->id);
        })->where('status', 'pending')->count();

        // Total Sales (number of completed orders)
        $totalSales = OrderItem::whereHas('food', function ($query) use ($vendor) {
            $query->where('user_id', $vendor->id);
        })->whereHas('order', function ($query) {
            $query->where('status', 'delivered');
        })->count();

        // Total Revenue (sum of sale price minus production cost)
        $totalRevenue = OrderItem::whereHas('food', function ($query) use ($vendor) {
            $query->where('user_id', $vendor->id);
        })->whereHas('order', function ($query) {
            $query->where('status', 'delivered');
        })->get()->sum(function ($item) {
            $price = $item->food->price ?? 0;
            $productionCost = $item->food->production_cost ?? 0;
            $quantity = $item->quantity ?? 1;
            return ($price - $productionCost) * $quantity;
        });

        return [
            Stat::make('Pending Orders', $pendingOrders)
                ->description('Orders waiting for confirmation')
                ->color('warning'),

            Stat::make('Total Sales', $totalSales)
                ->description('Number of completed orders')
                ->color('success'),

            Stat::make('Total Revenue', number_format($totalRevenue, 2) . ' CHF')
                ->description('Profit after production cost')
                ->color('primary'),
        ];
    }
}
