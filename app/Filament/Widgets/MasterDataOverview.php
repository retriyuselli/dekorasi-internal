<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Prospect;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Warehouse;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MasterDataOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int|array|null $columns = [
        'default' => 1,
        'sm' => 2,
        'xl' => 4,
    ];

    protected function getStats(): array
    {
        $user = auth()->user();
        $stats = [];

        if ($user instanceof User && $user->can('viewAny', Prospect::class)) {
            $stats[] = Stat::make('Prospek', (string) Prospect::query()->count());
        }

        if ($user instanceof User && $user->can('viewAny', Customer::class)) {
            $stats[] = Stat::make('Pelanggan', (string) Customer::query()->count());
        }

        if ($user instanceof User && $user->can('viewAny', Vendor::class)) {
            $stats[] = Stat::make('Vendor', (string) Vendor::query()->count());
        }

        if ($user instanceof User && $user->can('viewAny', Warehouse::class)) {
            $stats[] = Stat::make('Gudang', (string) Warehouse::query()->count());
        }

        if ($user instanceof User && $user->can('viewAny', Category::class)) {
            $stats[] = Stat::make('Kategori', (string) Category::query()->count());
        }

        return $stats;
    }
}
