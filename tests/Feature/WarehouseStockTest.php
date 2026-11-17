<?php

namespace Tests\Feature;

use App\Enums\StockCondition;
use App\Filament\Resources\Warehouses\Pages\ViewWarehouse;
use App\Filament\Resources\Warehouses\RelationManagers\LocationsRelationManager;
use App\Filament\Resources\Warehouses\RelationManagers\StocksRelationManager;
use App\Models\Category;
use App\Models\Item;
use App\Models\Stock;
use App\Models\Unit;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseLocation;
use App\Support\Money;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Tests\TestCase;

class WarehouseStockTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_nominal_is_quantity_times_purchase_price(): void
    {
        $this->assertSame('720000.00', Stock::calculateNominal(48, 15000));
        $this->assertSame('3600000.00', Stock::calculateNominal(2, '1.800.000'));
        $this->assertSame(1800000.0, Money::amount('1.800.000'));
        $this->assertSame(15000.0, Money::amount('15000.00'));
        $this->assertSame('1.800.000', Money::grouped(1800000));
        $this->assertSame('15.000', Money::grouped('15000.00'));
        $this->assertSame('Rp720.000', Money::rupiah('720000.00'));

        $warehouse = Warehouse::query()->where('code', 'GD-KURSI')->firstOrFail();
        $stock = Stock::query()->where('warehouse_id', $warehouse->id)->where('condition', StockCondition::Good)->firstOrFail();

        $this->assertSame('720000.00', $stock->nominal);
        $this->assertSame('720000.00', $warehouse->nominalTotal());
        $this->assertSame(1, $warehouse->itemCount());
        $this->assertSame(1, $stock->item->stocks()->count());
    }

    public function test_warehouse_user_can_place_stock_and_sales_cannot_open_items(): void
    {
        $warehouseUser = User::query()->where('email', 'gudang.agus@dekorasi.local')->firstOrFail();
        $sales = User::query()->where('email', 'sales.dimas@dekorasi.local')->firstOrFail();
        $warehouse = Warehouse::query()->where('code', 'GD-UTAMA')->firstOrFail();
        $item = Item::factory()->create([
            'company_id' => $warehouse->company_id,
            'category_id' => Category::query()->where('slug', 'backdrop')->value('id'),
            'unit_id' => Unit::query()->where('symbol', 'set')->value('id'),
            'code' => 'UJI-STOK',
            'name' => 'Barang Uji Stok',
        ]);

        $this->actingAs($sales)->get('/admin/items')->assertForbidden();
        $this->actingAs($warehouseUser);
        $this->assertTrue(StocksRelationManager::canViewForRecord($warehouse, ViewWarehouse::class));
        $this->assertTrue(LocationsRelationManager::canViewForRecord($warehouse, ViewWarehouse::class));

        Livewire::actingAs($warehouseUser)
            ->test(StocksRelationManager::class, [
                'ownerRecord' => $warehouse,
                'pageClass' => ViewWarehouse::class,
            ])
            ->callTableAction('create', data: [
                'item_id' => $item->id,
                'quantity' => 2,
                'purchase_price' => '100.000',
                'condition' => StockCondition::Good->value,
            ])
            ->assertHasNoTableActionErrors();

        $stock = Stock::query()->where('item_id', $item->id)->first();

        $this->assertNotNull($stock);
        $this->assertSame($warehouse->id, $stock->warehouse_id);
        $this->assertSame('200000.00', $stock->nominal);
        $this->assertNull($stock->warehouse_location_id);

        $existing = Stock::query()->whereHas('item', fn ($query) => $query->where('code', 'KRS-TIFFANY'))
            ->where('condition', StockCondition::Good)
            ->firstOrFail();

        Livewire::actingAs($warehouseUser)
            ->test(StocksRelationManager::class, [
                'ownerRecord' => $existing->warehouse,
                'pageClass' => ViewWarehouse::class,
            ])
            ->mountTableAction('edit', $existing)
            ->assertTableActionDataSet([
                'purchase_price' => '15.000',
            ]);
    }

    public function test_stock_form_only_offers_items_that_are_not_in_a_warehouse(): void
    {
        $free = Item::factory()->create([
            'company_id' => Warehouse::query()->value('company_id'),
            'category_id' => Category::query()->value('id'),
            'unit_id' => Unit::query()->value('id'),
            'code' => 'BEBAS-GUDANG',
            'name' => 'Barang Belum Masuk Gudang',
        ]);
        $placed = Item::query()->where('code', 'KRS-TIFFANY')->firstOrFail();
        $placedStock = $placed->stocks()->firstOrFail();

        $this->assertTrue(Item::query()->unassigned()->whereKey($free->id)->exists());
        $this->assertFalse(Item::query()->unassigned()->whereKey($placed->id)->exists());
        $this->assertTrue(Item::query()->unassigned($placedStock->id)->whereKey($placed->id)->exists());
        $this->assertFalse(
            Item::query()->unassigned()->where('code', 'TND-SARNAFIL')->exists(),
        );
        $this->assertTrue(Item::query()->unassigned()->where('code', 'TND-LIPAT')->exists());
        $this->assertTrue(Item::query()->unassigned()->where('code', 'BNG-STAND')->exists());
        $this->assertTrue(Item::query()->unassigned()->where('code', 'SGN-WELCOME')->exists());
    }

    public function test_an_item_already_stored_cannot_be_added_to_a_warehouse_again(): void
    {
        $existing = Stock::query()->whereHas('item', fn ($query) => $query->where('code', 'KRS-TIFFANY'))
            ->where('condition', StockCondition::Good)
            ->firstOrFail();

        try {
            Stock::query()->create([
                'company_id' => $existing->company_id,
                'warehouse_id' => Warehouse::query()->where('code', 'GD-TRANSIT')->value('id'),
                'item_id' => $existing->item_id,
                'quantity' => 1,
                'purchase_price' => 15000,
                'condition' => StockCondition::Good,
            ]);
            $this->fail('Barang yang sudah tersimpan masih bisa ditambahkan.');
        } catch (ValidationException $exception) {
            $this->assertSame('Barang ini sudah tersimpan di gudang lain.', $exception->errors()['item_id'][0]);
        }
    }

    public function test_a_location_that_still_holds_stock_cannot_be_deleted(): void
    {
        $location = WarehouseLocation::query()->where('code', 'RAK-TIFFANY')->firstOrFail();

        $this->expectException(ValidationException::class);

        $location->delete();
    }
}
