<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Warehouse;
use App\Support\DecoraModules;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\Models\Activity;

class DecoraHomeController extends Controller
{
    public function dashboard(): View
    {
        return view('decora.dashboard', [
            'activities' => $this->activities(),
            'warehouseCount' => Schema::hasTable('warehouses') ? Warehouse::query()->count() : 0,
            'modules' => [
                'projects' => DecoraModules::projects(),
                'quotations' => DecoraModules::quotations(),
                'revenue' => DecoraModules::revenue(),
                'inventory' => DecoraModules::inventory(),
            ],
        ]);
    }

    public function module(string $module): View
    {
        $pages = [
            'proyek' => 'Proyek Dekorasi',
            'logistik' => 'Logistik',
            'crew' => 'Crew & Produksi',
            'penawaran' => 'Penawaran & Invoice',
            'laporan' => 'Laporan',
        ];

        abort_unless(isset($pages[$module]), 404);

        return view('decora.module', [
            'title' => $pages[$module],
        ]);
    }

    public function inventory(Request $request): View
    {
        return view('decora.inventory', [
            'warehouseCount' => Schema::hasTable('warehouses') ? Warehouse::query()->count() : 0,
            'categories' => Schema::hasTable('categories')
                ? Category::query()->orderBy('name')->pluck('name', 'id')
                : collect(),
            'warehouses' => Schema::hasTable('warehouses')
                ? Warehouse::query()->orderBy('name')->pluck('name', 'id')
                : collect(),
            'filters' => [
                'q' => (string) $request->string('q'),
                'kategori' => (string) $request->string('kategori'),
                'gudang' => (string) $request->string('gudang'),
                'status' => (string) $request->string('status'),
            ],
            'moduleReady' => DecoraModules::inventory(),
        ]);
    }

    /**
     * @return Collection<int, array{label: string, subject: string, actor: string, time: string}>
     */
    private function activities(): Collection
    {
        if (! Schema::hasTable('activity_log')) {
            return collect();
        }

        return Activity::query()
            ->with(['causer', 'subject'])
            ->latest()
            ->limit(6)
            ->get()
            ->map(function (Activity $activity): array {
                $name = $activity->subject?->name;
                $type = match (class_basename((string) $activity->subject_type)) {
                    'Prospect' => 'Prospek',
                    'Customer' => 'Pelanggan',
                    'Vendor' => 'Vendor',
                    'VendorItem' => 'Harga vendor',
                    'VendorDocument' => 'Dokumen vendor',
                    'Category' => 'Kategori',
                    'Unit' => 'Satuan',
                    'Warehouse' => 'Gudang',
                    'Item' => 'Barang',
                    'WarehouseLocation' => 'Penempatan',
                    'Stock' => 'Isi gudang',
                    'User' => 'Pengguna',
                    'Company' => 'Perusahaan',
                    default => 'Data',
                };

                return [
                    'label' => match ((string) $activity->event) {
                        'created' => 'Dibuat',
                        'updated' => 'Diubah',
                        'deleted' => 'Dihapus',
                        'restored' => 'Dipulihkan',
                        default => $activity->event !== null && $activity->event !== ''
                            ? str($activity->event)->headline()->toString()
                            : 'Perubahan',
                    },
                    'subject' => filled($name) ? $type.': '.$name : $type,
                    'actor' => $activity->causer?->name ?: 'Sistem',
                    'time' => $activity->created_at?->locale('id')->diffForHumans() ?? '',
                ];
            });
    }
}
