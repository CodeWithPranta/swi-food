<?php

namespace App\Filament\Vendor\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue Over Time';

    public ?string $filter = 'month';

    protected function getFilters(): ?array
    {
        return [
            'day' => 'Today',
            'week' => 'Last Week',
            'month' => 'Last Month',
            'year' => 'This Year',
        ];
    }

    protected function getData(): array
    {
        $vendor = Auth::user();

        // Get order items for this vendor and delivered orders
        $query = OrderItem::with('food')
            ->whereHas('food', fn($q) => $q->where('user_id', $vendor->id))
            ->whereHas('order', fn($q) => $q->where('status', 'delivered'));

        $orders = match ($this->filter) {
            'day' => $query->whereDate('created_at', today())->get(),
            'week' => $query->whereBetween('created_at', [now()->subWeek(), now()])->get(),
            'month' => $query->whereMonth('created_at', now()->month)
                              ->whereYear('created_at', now()->year)
                              ->get(),
            'year' => $query->whereYear('created_at', now()->year)->get(),
            default => collect(),
        };

        // Group and sum manually because production_cost is on foods table
        $grouped = $orders->groupBy(function ($item) {
            return match ($this->filter) {
                'day' => $item->created_at->format('H'),       // hourly
                'week', 'month' => $item->created_at->format('Y-m-d'), // daily
                'year' => $item->created_at->format('M'),      // monthly
                default => $item->created_at->format('Y-m-d'),
            };
        });

        $labels = $grouped->keys()->toArray();
        $data = $grouped->map(function ($items) {
            return $items->sum(fn($item) => (($item->food->price ?? 0) - ($item->food->production_cost ?? 0)) * $item->quantity);
        })->values()->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $data,
                    'backgroundColor' => '#10B981',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
