<?php

namespace App\Filament\Vendor\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class SaleChart extends ChartWidget
{
    protected static ?string $heading = 'Sales Over Time';

    public ?string $filter = 'month'; // Default filter

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

        // Base query: delivered orders for this vendor
        $query = OrderItem::whereHas('food', fn($q) => $q->where('user_id', $vendor->id))
            ->whereHas('order', fn($q) => $q->where('status', 'delivered'));

        $items = match ($this->filter) {
            'day' => $query->whereDate('created_at', today())->get(),
            'week' => $query->whereBetween('created_at', [now()->subWeek(), now()])->get(),
            'month' => $query->whereMonth('created_at', now()->month)
                              ->whereYear('created_at', now()->year)
                              ->get(),
            'year' => $query->whereYear('created_at', now()->year)->get(),
            default => collect(),
        };

        // Group and sum by time period
        $grouped = $items->groupBy(fn($item) => match ($this->filter) {
            'day' => $item->created_at->format('H'),       // hourly
            'week', 'month' => $item->created_at->format('Y-m-d'), // daily
            'year' => $item->created_at->format('M'),      // monthly
            default => $item->created_at->format('Y-m-d'),
        });

        $labels = $grouped->keys()->toArray();

        // Sum total quantity sold (or total sales value if you prefer)
        $data = $grouped->map(fn($items) => $items->sum(fn($i) => $i->quantity))->values()->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Total Sales',
                    'data' => $data,
                    'borderColor' => '#4F46E5',
                    'backgroundColor' => 'rgba(79, 70, 229, 0.1)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
