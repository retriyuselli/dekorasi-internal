<?php

namespace Tests\Feature;

use App\Filament\Resources\Vendors\Pages\ViewVendor;
use App\Filament\Resources\Vendors\RelationManagers\DocumentsRelationManager;
use App\Filament\Resources\Vendors\RelationManagers\ItemsRelationManager;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorDocument;
use App\Models\VendorItem;
use App\Support\Money;
use App\Support\Roles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class VendorOfferTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_gross_profit_is_the_margin_times_quantity(): void
    {
        $this->assertSame('3000000.00', VendorItem::calculateGrossProfit(1500000, 750000, 4));
        $this->assertSame('-100000.00', VendorItem::calculateGrossProfit(100000, 150000, 2));
        $this->assertSame('Rp3.000.000', Money::rupiah('3000000.00'));
        $this->assertSame('-Rp100.000', Money::rupiah('-100000.00'));
    }

    public function test_seeded_vendor_items_keep_a_gross_profit(): void
    {
        $item = VendorItem::query()->where('name', 'Tenda sarnafil 6×6 m')->firstOrFail();

        $this->assertSame('6000000.00', $item->gross_profit);
        $this->assertSame('8700000.00', $item->vendor->grossProfitTotal());
    }

    public function test_admin_can_add_a_priced_item_and_sales_can_only_view_it(): void
    {
        $admin = User::query()->where('email', 'admin@dekorasi.local')->firstOrFail();
        $sales = User::query()->where('email', 'sales.dimas@dekorasi.local')->firstOrFail();
        $vendor = Vendor::factory()->create(['name' => 'Vendor Harga Uji']);

        $this->actingAs($admin);
        $this->assertTrue(ItemsRelationManager::canViewForRecord($vendor, ViewVendor::class));
        $this->assertTrue(DocumentsRelationManager::canViewForRecord($vendor, ViewVendor::class));

        Livewire::actingAs($admin)
            ->test(ItemsRelationManager::class, [
                'ownerRecord' => $vendor,
                'pageClass' => ViewVendor::class,
            ])
            ->callTableAction('create', data: [
                'name' => 'Backdrop kayu',
                'cost_price' => '500.000',
                'sell_price' => '900.000',
                'quantity' => 2,
            ])
            ->assertHasNoTableActionErrors();

        $item = VendorItem::query()->where('name', 'Backdrop kayu')->first();

        $this->assertNotNull($item);
        $this->assertSame($vendor->id, $item->vendor_id);
        $this->assertSame('800000.00', $item->gross_profit);

        Livewire::actingAs($sales)
            ->test(ItemsRelationManager::class, [
                'ownerRecord' => $vendor,
                'pageClass' => ViewVendor::class,
            ])
            ->assertTableActionHidden('create');
    }

    public function test_cooperation_document_is_stored_privately_and_can_be_downloaded_by_name(): void
    {
        Storage::fake('local');

        $admin = User::query()->where('email', 'admin@dekorasi.local')->firstOrFail();
        $staff = User::factory()->create();
        $staff->assignRole(Roles::STAFF);
        $vendor = Vendor::factory()->create(['name' => 'Vendor Dokumen Uji']);
        $file = UploadedFile::fake()->create('perjanjian.pdf', 20, 'application/pdf');

        Livewire::actingAs($admin)
            ->test(DocumentsRelationManager::class, [
                'ownerRecord' => $vendor,
                'pageClass' => ViewVendor::class,
            ])
            ->callTableAction('create', data: [
                'title' => 'Perjanjian sewa tenda',
                'file_path' => [$file],
                'notes' => 'Berlaku satu tahun.',
            ])
            ->assertHasNoTableActionErrors();

        $document = VendorDocument::query()->where('title', 'Perjanjian sewa tenda')->first();

        $this->assertNotNull($document);
        $this->assertSame($vendor->id, $document->vendor_id);
        $this->assertTrue(Storage::disk('local')->exists($document->file_path));
        $this->assertFalse(str_starts_with($document->file_path, 'public/'));

        $this->actingAs($staff);
        $this->assertFalse(DocumentsRelationManager::canViewForRecord($vendor, ViewVendor::class));
    }
}
