<?php

namespace Database\Seeders;

use App\Enums\CustomerType;
use App\Enums\ProspectStatus;
use App\Enums\StockCondition;
use App\Enums\VendorCategory;
use App\Models\Category;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Prospect;
use App\Models\Stock;
use App\Models\Unit;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorItem;
use App\Models\Warehouse;
use App\Models\WarehouseLocation;
use App\Support\Roles;
use Illuminate\Database\Seeder;

class DecorationDemoSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::query()->where('email', 'admin@dekorasi.local')->firstOrFail();

        Customer::query()->where('email', 'pelanggan@contoh.test')->forceDelete();
        Vendor::query()->where('email', 'vendor@contoh.test')->forceDelete();

        $this->seedUsers($company);
        $this->seedProspects($company);
        $this->seedCustomers($company);
        $this->seedVendors($company);
        $this->seedCategories();
        $this->seedUnits();
        $this->seedVendorItems($company);
        $this->seedWarehouses($company);
        $this->seedWarehouseContents($company);
    }

    private function seedUsers(Company $company): void
    {
        $users = [
            ['name' => 'Laksmi Dewi', 'email' => 'direktur@dekorasi.local', 'role' => Roles::DIRECTOR],
            ['name' => 'Dimas Pratama', 'email' => 'sales.dimas@dekorasi.local', 'role' => Roles::SALES],
            ['name' => 'Intan Permata', 'email' => 'sales.intan@dekorasi.local', 'role' => Roles::SALES],
            ['name' => 'Reza Mahendra', 'email' => 'pm.reza@dekorasi.local', 'role' => Roles::PROJECT_MANAGER],
            ['name' => 'Nadia Kusuma', 'email' => 'pm.nadia@dekorasi.local', 'role' => Roles::PROJECT_MANAGER],
            ['name' => 'Agus Setiawan', 'email' => 'gudang.agus@dekorasi.local', 'role' => Roles::WAREHOUSE],
            ['name' => 'Wulan Sari', 'email' => 'gudang.wulan@dekorasi.local', 'role' => Roles::WAREHOUSE],
            ['name' => 'Hendra Wijaya', 'email' => 'keuangan.hendra@dekorasi.local', 'role' => Roles::FINANCE],
            ['name' => 'Putri Ayu', 'email' => 'staf.putri@dekorasi.local', 'role' => Roles::STAFF],
            ['name' => 'Fajar Ramadhan', 'email' => 'staf.fajar@dekorasi.local', 'role' => Roles::STAFF],
        ];

        foreach ($users as $row) {
            $user = User::query()->firstOrNew(['email' => $row['email']]);
            $user->company_id = $company->id;
            $user->name = $row['name'];
            $user->is_active = true;

            if (! $user->exists) {
                $user->password = env('ADMIN_PASSWORD', 'password');
            }

            $user->save();
            $user->syncRoles([$row['role']]);
        }
    }

    private function seedProspects(Company $company): void
    {
        $prospects = [
            ['name' => 'Ayu & Fajar', 'type' => CustomerType::Individual, 'email' => 'ayu.fajar@prospek.test', 'phone' => '081230010001', 'address' => 'Cilandak, Jakarta Selatan', 'event_interest' => 'Pernikahan garden di Royal Ambarrukmo', 'status' => ProspectStatus::New, 'notes' => 'Baru minta kisaran backdrop dan pelaminan.'],
            ['name' => 'PT Langit Cerah', 'type' => CustomerType::Company, 'email' => 'hr@langitcerah.test', 'phone' => '0215003002', 'address' => 'Sudirman, Jakarta Pusat', 'event_interest' => 'Gathering karyawan', 'status' => ProspectStatus::Contacted, 'notes' => 'Sudah dihubungi, menunggu tanggal venue.'],
            ['name' => 'Keluarga Wijaya', 'type' => CustomerType::Individual, 'email' => 'wijaya@prospek.test', 'phone' => '081230010003', 'address' => 'Bogor', 'event_interest' => 'Akad dan resepsi adat', 'status' => ProspectStatus::Interested, 'notes' => 'Tertarik paket pelaminan dan bunga.'],
            ['name' => 'Dinas Kebudayaan Sleman', 'type' => CustomerType::Company, 'email' => 'pameran@kebudayaan.sleman.test', 'phone' => '02745003004', 'address' => 'Sleman, Yogyakarta', 'event_interest' => 'Pameran budaya', 'status' => ProspectStatus::Contacted, 'notes' => 'Butuh booth dan signage.'],
            ['name' => 'Sinta Maharani', 'type' => CustomerType::Individual, 'email' => 'sinta.maharani@prospek.test', 'phone' => '081230010005', 'address' => 'Semarang', 'event_interest' => 'Lamaran', 'status' => ProspectStatus::New, 'notes' => 'Menunggu konsep warna dari keluarga.'],
            ['name' => 'Hotel Pantai Senja', 'type' => CustomerType::Company, 'email' => 'banquet@pantaisenja.test', 'phone' => '03615003006', 'address' => 'Kuta, Bali', 'event_interest' => 'Paket wedding ballroom', 'status' => ProspectStatus::Interested, 'notes' => 'Minta contoh lighting dan drapery.'],
            ['name' => 'Komunitas Fotografi Jogja', 'type' => CustomerType::Company, 'email' => 'acara@fotografi.jogja.test', 'phone' => '081230010007', 'address' => 'Yogyakarta', 'event_interest' => 'Pameran foto', 'status' => ProspectStatus::Lost, 'notes' => 'Anggaran belum cukup, ditunda tahun depan.'],
            ['name' => 'Raka Pratama', 'type' => CustomerType::Individual, 'email' => 'raka.pratama@prospek.test', 'phone' => '081230010008', 'address' => 'Depok', 'event_interest' => 'Syukuran rumah', 'status' => ProspectStatus::New, 'notes' => 'Butuh tenda dan kursi tamu.'],
            ['name' => 'Yayasan Cahaya Ilmu', 'type' => CustomerType::Company, 'email' => 'acara@cahayailmu.test', 'phone' => '0215003009', 'address' => 'Tangerang Selatan', 'event_interest' => 'Malam penghargaan', 'status' => ProspectStatus::Contacted, 'notes' => 'Panggung, backdrop, dan standing lamp.'],
            ['name' => 'CV Rimba Dekor', 'type' => CustomerType::Company, 'email' => 'mitra@rimbadekor.test', 'phone' => '081230010010', 'address' => 'Malang', 'event_interest' => 'Kerja sama wedding organizer', 'status' => ProspectStatus::Interested, 'notes' => 'Ingin jadi mitra pasokan dekor.'],
        ];

        foreach ($prospects as $row) {
            Prospect::query()->updateOrCreate(
                ['company_id' => $company->id, 'email' => $row['email']],
                $row,
            );
        }
    }

    private function seedCustomers(Company $company): void
    {
        $customers = [
            ['name' => 'Rina & Andika', 'type' => CustomerType::Individual, 'email' => 'rina.andika@acara.test', 'phone' => '081210010001', 'address' => 'Kemang, Jakarta Selatan', 'notes' => 'Resepsi pernikahan, konsep garden party.'],
            ['name' => 'Keluarga Hartono', 'type' => CustomerType::Individual, 'email' => 'hartono@acara.test', 'phone' => '081210010002', 'address' => 'Menteng, Jakarta Pusat', 'notes' => 'Akad dan resepsi di rumah.'],
            ['name' => 'PT Sinar Acara Indonesia', 'type' => CustomerType::Company, 'email' => 'event@sinaracara.test', 'phone' => '0215001003', 'address' => 'SCBD, Jakarta Selatan', 'notes' => 'Gathering tahunan karyawan.'],
            ['name' => 'Sarah Melati', 'type' => CustomerType::Individual, 'email' => 'sarah.melati@acara.test', 'phone' => '081210010004', 'address' => 'Bandung', 'notes' => 'Lamaran adat Sunda.'],
            ['name' => 'Dinas Pariwisata Kota Bandung', 'type' => CustomerType::Company, 'email' => 'pameran@dispar.bandung.test', 'phone' => '0225001005', 'address' => 'Balai Kota Bandung', 'notes' => 'Booth pameran pariwisata.'],
            ['name' => 'Hotel Grand Melati', 'type' => CustomerType::Company, 'email' => 'banquet@grandmelati.test', 'phone' => '0315001006', 'address' => 'Surabaya', 'notes' => 'Dekor ballroom untuk wedding package.'],
            ['name' => 'Komunitas Seniman Muda', 'type' => CustomerType::Company, 'email' => 'pameran@senimanmuda.test', 'phone' => '081210010007', 'address' => 'Yogyakarta', 'notes' => 'Pameran seni dan instalasi bunga.'],
            ['name' => 'Budi Santoso', 'type' => CustomerType::Individual, 'email' => 'budi.santoso@acara.test', 'phone' => '081210010008', 'address' => 'Bekasi', 'notes' => 'Syukuran rumah baru.'],
            ['name' => 'Yayasan Pelita Kasih', 'type' => CustomerType::Company, 'email' => 'acara@pelitakasih.test', 'phone' => '0215001009', 'address' => 'Tangerang', 'notes' => 'Malam amal, panggung dan backdrop.'],
            ['name' => 'CV Nuansa Pesta', 'type' => CustomerType::Company, 'email' => 'proyek@nuansapesta.test', 'phone' => '03615001010', 'address' => 'Denpasar, Bali', 'notes' => 'Mitra wedding organizer, butuh stok dekor.'],
        ];

        foreach ($customers as $row) {
            Customer::query()->updateOrCreate(
                ['company_id' => $company->id, 'email' => $row['email']],
                $row,
            );
        }
    }

    private function seedVendors(Company $company): void
    {
        $vendors = [
            ['name' => 'Sewa Tenda Abadi', 'category' => VendorCategory::Rental, 'contact_person' => 'Pak Darma', 'email' => 'tenda@abadi.test', 'phone' => '081220010001', 'address' => 'Cakung, Jakarta Timur'],
            ['name' => 'Bunga Lestari', 'category' => VendorCategory::Flowers, 'contact_person' => 'Mira Lestari', 'email' => 'order@bungalestari.test', 'phone' => '081220010002', 'address' => 'Pasar Bunga Rawa Belong'],
            ['name' => 'Percetakan Aksara', 'category' => VendorCategory::Printing, 'contact_person' => 'Yudi Aksara', 'email' => 'cetak@aksara.test', 'phone' => '0215002003', 'address' => 'Palmerah, Jakarta Barat'],
            ['name' => 'Armada Dekor Logistik', 'category' => VendorCategory::Transport, 'contact_person' => 'Eko Prasetyo', 'email' => 'jadwal@armadadekor.test', 'phone' => '081220010004', 'address' => 'Cibitung, Bekasi'],
            ['name' => 'Lighting Panggung Jaya', 'category' => VendorCategory::Rental, 'contact_person' => 'Rian Jaya', 'email' => 'sewa@lightingjaya.test', 'phone' => '081220010005', 'address' => 'Tebet, Jakarta Selatan'],
            ['name' => 'Kain Drapery Nusantara', 'category' => VendorCategory::Other, 'contact_person' => 'Sinta Wijaya', 'email' => 'kain@drapery.test', 'phone' => '0225002006', 'address' => 'Cigondewah, Bandung'],
            ['name' => 'Sewa Kursi Pelaminan', 'category' => VendorCategory::Rental, 'contact_person' => 'Hendra Kursi', 'email' => 'sewa@kursipelaminan.test', 'phone' => '081220010007', 'address' => 'Sidoarjo'],
            ['name' => 'Florist Mawar Putih', 'category' => VendorCategory::Flowers, 'contact_person' => 'Ayu Mawar', 'email' => 'florist@mawarputih.test', 'phone' => '081220010008', 'address' => 'Ubud, Bali'],
            ['name' => 'Cetak Spanduk Cepat', 'category' => VendorCategory::Printing, 'contact_person' => 'Doni Cetak', 'email' => 'spanduk@cepat.test', 'phone' => '02745002009', 'address' => 'Yogyakarta'],
            ['name' => 'Ekspedisi Barang Acara', 'category' => VendorCategory::Transport, 'contact_person' => 'Lia Ekspedisi', 'email' => 'kirim@barangacara.test', 'phone' => '081220010010', 'address' => 'Medan'],
        ];

        foreach ($vendors as $row) {
            Vendor::query()->updateOrCreate(
                ['company_id' => $company->id, 'email' => $row['email']],
                $row,
            );
        }
    }

    private function seedVendorItems(Company $company): void
    {
        $unit = fn (string $symbol): ?int => Unit::query()->where('symbol', $symbol)->value('id');

        $items = [
            'tenda@abadi.test' => [
                ['name' => 'Tenda sarnafil 6×6 m', 'unit' => 'set', 'cost_price' => 750000, 'sell_price' => 1500000, 'quantity' => 8],
                ['name' => 'Tenda semi dekor 4×6 m', 'unit' => 'set', 'cost_price' => 450000, 'sell_price' => 900000, 'quantity' => 6],
            ],
            'order@bungalestari.test' => [
                ['name' => 'Rangkaian bunga meja tamu', 'unit' => 'pcs', 'cost_price' => 75000, 'sell_price' => 150000, 'quantity' => 40],
            ],
            'cetak@aksara.test' => [
                ['name' => 'Undangan cetak', 'unit' => 'lbr', 'cost_price' => 8000, 'sell_price' => 18000, 'quantity' => 200],
            ],
            'jadwal@armadadekor.test' => [
                ['name' => 'Angkut barang dekor', 'unit' => 'paket', 'cost_price' => 600000, 'sell_price' => 950000, 'quantity' => 2],
            ],
            'sewa@lightingjaya.test' => [
                ['name' => 'Paket lighting panggung', 'unit' => 'paket', 'cost_price' => 1200000, 'sell_price' => 2200000, 'quantity' => 1],
            ],
            'kain@drapery.test' => [
                ['name' => 'Kain drapery', 'unit' => 'm', 'cost_price' => 35000, 'sell_price' => 65000, 'quantity' => 80],
            ],
            'sewa@kursipelaminan.test' => [
                ['name' => 'Kursi Tiffany', 'unit' => 'pcs', 'cost_price' => 15000, 'sell_price' => 35000, 'quantity' => 100],
            ],
            'florist@mawarputih.test' => [
                ['name' => 'Standing flower', 'unit' => 'pcs', 'cost_price' => 250000, 'sell_price' => 450000, 'quantity' => 6],
            ],
            'spanduk@cepat.test' => [
                ['name' => 'Spanduk acara', 'unit' => 'lbr', 'cost_price' => 45000, 'sell_price' => 85000, 'quantity' => 10],
            ],
            'kirim@barangacara.test' => [
                ['name' => 'Pengiriman antar kota', 'unit' => 'paket', 'cost_price' => 900000, 'sell_price' => 1300000, 'quantity' => 1],
            ],
        ];

        foreach ($items as $email => $rows) {
            $vendor = Vendor::query()->where('company_id', $company->id)->where('email', $email)->first();

            if ($vendor === null) {
                continue;
            }

            foreach ($rows as $row) {
                VendorItem::query()->updateOrCreate(
                    ['vendor_id' => $vendor->id, 'name' => $row['name']],
                    [
                        'company_id' => $company->id,
                        'unit_id' => $unit($row['unit']),
                        'cost_price' => $row['cost_price'],
                        'sell_price' => $row['sell_price'],
                        'quantity' => $row['quantity'],
                    ],
                );
            }
        }
    }

    private function seedCategories(): void
    {
        $parent = Category::query()->updateOrCreate(
            ['slug' => 'dekorasi'],
            ['name' => 'Dekorasi', 'parent_id' => null, 'is_active' => true],
        );

        $children = [
            ['name' => 'Backdrop', 'slug' => 'backdrop'],
            ['name' => 'Pelaminan', 'slug' => 'pelaminan'],
            ['name' => 'Tenda', 'slug' => 'tenda'],
            ['name' => 'Lighting', 'slug' => 'lighting'],
            ['name' => 'Bunga', 'slug' => 'bunga'],
            ['name' => 'Furnitur', 'slug' => 'furnitur'],
            ['name' => 'Panggung', 'slug' => 'panggung'],
            ['name' => 'Signage', 'slug' => 'signage'],
            ['name' => 'Aksesoris', 'slug' => 'aksesoris'],
        ];

        foreach ($children as $row) {
            Category::query()->updateOrCreate(
                ['slug' => $row['slug']],
                ['name' => $row['name'], 'parent_id' => $parent->id, 'is_active' => true],
            );
        }
    }

    private function seedUnits(): void
    {
        $units = [
            ['name' => 'Buah', 'symbol' => 'pcs'],
            ['name' => 'Set', 'symbol' => 'set'],
            ['name' => 'Meter', 'symbol' => 'm'],
            ['name' => 'Roll', 'symbol' => 'roll'],
            ['name' => 'Lembar', 'symbol' => 'lbr'],
            ['name' => 'Botol', 'symbol' => 'btl'],
            ['name' => 'Dus', 'symbol' => 'dus'],
            ['name' => 'Meter persegi', 'symbol' => 'm2'],
            ['name' => 'Kilogram', 'symbol' => 'kg'],
            ['name' => 'Paket', 'symbol' => 'paket'],
        ];

        foreach ($units as $unit) {
            Unit::query()->updateOrCreate(['symbol' => $unit['symbol']], $unit);
        }
    }

    private function seedWarehouses(Company $company): void
    {
        $warehouses = [
            ['name' => 'Gudang Utama', 'code' => 'GD-UTAMA', 'address' => 'Gudang pusat, Jakarta Timur'],
            ['name' => 'Gudang Tenda', 'code' => 'GD-TENDA', 'address' => 'Cakung, Jakarta Timur'],
            ['name' => 'Gudang Bunga', 'code' => 'GD-BUNGA', 'address' => 'Rawa Belong, Jakarta Barat'],
            ['name' => 'Gudang Kain', 'code' => 'GD-KAIN', 'address' => 'Cigondewah, Bandung'],
            ['name' => 'Gudang Lighting', 'code' => 'GD-LAMPU', 'address' => 'Tebet, Jakarta Selatan'],
            ['name' => 'Gudang Furnitur', 'code' => 'GD-KURSI', 'address' => 'Sidoarjo'],
            ['name' => 'Gudang Panggung', 'code' => 'GD-PANGGUNG', 'address' => 'Cibitung, Bekasi'],
            ['name' => 'Gudang Signage', 'code' => 'GD-SIGN', 'address' => 'Palmerah, Jakarta Barat'],
            ['name' => 'Gudang Aksesoris', 'code' => 'GD-AKSESORIS', 'address' => 'Yogyakarta'],
            ['name' => 'Gudang Transit Lapangan', 'code' => 'GD-TRANSIT', 'address' => 'Denpasar, Bali'],
        ];

        foreach ($warehouses as $row) {
            Warehouse::query()->updateOrCreate(
                ['code' => $row['code']],
                [
                    'company_id' => $company->id,
                    'name' => $row['name'],
                    'address' => $row['address'],
                    'is_active' => true,
                ],
            );
        }
    }

    private function seedWarehouseContents(Company $company): void
    {
        $category = fn (string $slug): int => (int) Category::query()->where('slug', $slug)->value('id');
        $unit = fn (string $symbol): int => (int) Unit::query()->where('symbol', $symbol)->value('id');
        $vendor = fn (string $email): ?int => Vendor::query()->where('email', $email)->value('id');

        $items = [
            ['code' => 'KRS-TIFFANY', 'name' => 'Kursi Tiffany', 'category' => 'furnitur', 'unit' => 'pcs', 'vendor' => 'sewa@kursipelaminan.test', 'notes' => 'Kursi tamu resepsi, warna putih.'],
            ['code' => 'TND-SARNAFIL', 'name' => 'Tenda sarnafil 6×6 m', 'category' => 'tenda', 'unit' => 'set', 'vendor' => 'tenda@abadi.test', 'notes' => 'Tenda atap datar untuk pelataran.'],
            ['code' => 'TND-LIPAT', 'name' => 'Tenda lipat 3×3 m', 'category' => 'tenda', 'unit' => 'set', 'vendor' => 'tenda@abadi.test', 'notes' => 'Tenda cepat pasang.'],
            ['code' => 'BNG-MEJA', 'name' => 'Rangkaian bunga meja', 'category' => 'bunga', 'unit' => 'pcs', 'vendor' => 'order@bungalestari.test', 'notes' => 'Rangkaian rendah untuk meja tamu.'],
            ['code' => 'BNG-STAND', 'name' => 'Standing flower', 'category' => 'bunga', 'unit' => 'pcs', 'vendor' => 'florist@mawarputih.test', 'notes' => 'Bunga berdiri di sisi pelaminan.'],
            ['code' => 'BDP-KAYU', 'name' => 'Backdrop kayu 3×6 m', 'category' => 'backdrop', 'unit' => 'set', 'vendor' => null, 'notes' => 'Rangka kayu plus panel kain.'],
            ['code' => 'KAI-DRAPE', 'name' => 'Kain drapery', 'category' => 'aksesoris', 'unit' => 'm', 'vendor' => 'kain@drapery.test', 'notes' => 'Kain jatuh untuk backdrop dan plafon.'],
            ['code' => 'LMP-STAND', 'name' => 'Standing lamp', 'category' => 'lighting', 'unit' => 'pcs', 'vendor' => 'sewa@lightingjaya.test', 'notes' => 'Lampu berdiri warm white.'],
            ['code' => 'PNG-RANGKA', 'name' => 'Rangka panggung', 'category' => 'panggung', 'unit' => 'set', 'vendor' => null, 'notes' => 'Modul panggung 2×1 m.'],
            ['code' => 'SGN-WELCOME', 'name' => 'Welcome sign', 'category' => 'signage', 'unit' => 'pcs', 'vendor' => 'spanduk@cepat.test', 'notes' => 'Papan nama tamu di pintu masuk.'],
        ];

        $itemIds = [];

        foreach ($items as $row) {
            $item = Item::query()->updateOrCreate(
                ['company_id' => $company->id, 'code' => $row['code']],
                [
                    'category_id' => $category($row['category']),
                    'unit_id' => $unit($row['unit']),
                    'vendor_id' => $row['vendor'] ? $vendor($row['vendor']) : null,
                    'name' => $row['name'],
                    'notes' => $row['notes'],
                    'is_active' => true,
                ],
            );
            $itemIds[$row['code']] = $item->id;
        }

        $placements = [
            'GD-UTAMA' => [['code' => 'ZONA-UMUM', 'name' => 'Zona serbaguna']],
            'GD-TENDA' => [
                ['code' => 'RAK-SARNAFIL', 'name' => 'Rak sarnafil'],
                ['code' => 'RAK-LIPAT', 'name' => 'Rak tenda lipat'],
            ],
            'GD-BUNGA' => [['code' => 'RUANG-DINGIN', 'name' => 'Ruang dingin']],
            'GD-KAIN' => [['code' => 'RAK-KAIN', 'name' => 'Rak kain']],
            'GD-LAMPU' => [['code' => 'RAK-LAMPU', 'name' => 'Rak lighting']],
            'GD-KURSI' => [['code' => 'RAK-TIFFANY', 'name' => 'Rak Tiffany']],
            'GD-PANGGUNG' => [['code' => 'ZONA-RANGKA', 'name' => 'Zona rangka']],
            'GD-SIGN' => [['code' => 'RAK-SIGN', 'name' => 'Rak signage']],
            'GD-AKSESORIS' => [['code' => 'RAK-AKSESORIS', 'name' => 'Rak aksesoris']],
            'GD-TRANSIT' => [['code' => 'AREA-TRANSIT', 'name' => 'Area transit']],
        ];

        $locationIds = [];

        foreach ($placements as $warehouseCode => $rows) {
            $warehouse = Warehouse::query()->where('code', $warehouseCode)->first();

            if ($warehouse === null) {
                continue;
            }

            foreach ($rows as $row) {
                $location = WarehouseLocation::query()->updateOrCreate(
                    ['warehouse_id' => $warehouse->id, 'code' => $row['code']],
                    [
                        'company_id' => $company->id,
                        'name' => $row['name'],
                    ],
                );
                $locationIds[$row['code']] = $location->id;
            }
        }

        $stocks = [
            ['warehouse' => 'GD-KURSI', 'location' => 'RAK-TIFFANY', 'item' => 'KRS-TIFFANY', 'quantity' => 48, 'price' => 15000, 'condition' => StockCondition::Good, 'date' => '2026-02-10'],
            ['warehouse' => 'GD-TENDA', 'location' => 'RAK-SARNAFIL', 'item' => 'TND-SARNAFIL', 'quantity' => 8, 'price' => 750000, 'condition' => StockCondition::Good, 'date' => '2025-11-03'],
            ['warehouse' => 'GD-BUNGA', 'location' => 'RUANG-DINGIN', 'item' => 'BNG-MEJA', 'quantity' => 30, 'price' => 75000, 'condition' => StockCondition::Good, 'date' => '2026-06-01'],
            ['warehouse' => 'GD-UTAMA', 'location' => 'ZONA-UMUM', 'item' => 'BDP-KAYU', 'quantity' => 3, 'price' => 1800000, 'condition' => StockCondition::Good, 'date' => '2025-08-20'],
            ['warehouse' => 'GD-KAIN', 'location' => 'RAK-KAIN', 'item' => 'KAI-DRAPE', 'quantity' => 80, 'price' => 35000, 'condition' => StockCondition::Good, 'date' => '2026-01-15'],
            ['warehouse' => 'GD-LAMPU', 'location' => 'RAK-LAMPU', 'item' => 'LMP-STAND', 'quantity' => 10, 'price' => 275000, 'condition' => StockCondition::Good, 'date' => '2026-03-12'],
            ['warehouse' => 'GD-PANGGUNG', 'location' => 'ZONA-RANGKA', 'item' => 'PNG-RANGKA', 'quantity' => 4, 'price' => 2200000, 'condition' => StockCondition::Good, 'date' => '2025-09-01'],
        ];

        $keptStockIds = [];

        foreach ($stocks as $row) {
            $warehouse = Warehouse::query()->where('code', $row['warehouse'])->first();

            if ($warehouse === null) {
                continue;
            }

            $stock = Stock::query()->updateOrCreate(
                [
                    'warehouse_id' => $warehouse->id,
                    'warehouse_location_id' => $locationIds[$row['location']] ?? null,
                    'item_id' => $itemIds[$row['item']],
                    'condition' => $row['condition'],
                ],
                [
                    'company_id' => $company->id,
                    'quantity' => $row['quantity'],
                    'purchase_price' => $row['price'],
                    'purchased_at' => $row['date'],
                    'notes' => $row['notes'] ?? null,
                ],
            );
            $keptStockIds[] = $stock->id;
        }

        Stock::query()
            ->whereIn('item_id', array_values($itemIds))
            ->whereKeyNot($keptStockIds)
            ->get()
            ->each
            ->delete();
    }
}
