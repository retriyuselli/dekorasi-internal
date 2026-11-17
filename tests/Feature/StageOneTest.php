<?php

namespace Tests\Feature;

use App\Enums\CustomerType;
use App\Filament\Auth\Login;
use App\Filament\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ViewCategory;
use App\Filament\Resources\Categories\RelationManagers\ChildrenRelationManager;
use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\ViewCustomer;
use App\Models\Category;
use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use App\Models\Warehouse;
use App\Support\Roles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Spatie\Permission\Models\Role as SpatieRole;
use Tests\TestCase;

class StageOneTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guest_is_sent_to_the_login_page(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_active_super_admin_can_log_in(): void
    {
        $admin = User::query()->where('email', 'admin@dekorasi.local')->firstOrFail();

        Livewire::test(Login::class)
            ->fillForm([
                'email' => $admin->email,
                'password' => 'password',
            ])
            ->call('authenticate')
            ->assertHasNoFormErrors();

        $this->assertAuthenticatedAs($admin);
    }

    public function test_inactive_user_cannot_log_in(): void
    {
        $user = User::factory()->inactive()->create([
            'email' => 'nonaktif@dekorasi.local',
        ]);
        $user->assignRole(Roles::STAFF);

        Livewire::test(Login::class)
            ->fillForm([
                'email' => $user->email,
                'password' => 'password',
            ])
            ->call('authenticate')
            ->assertHasFormErrors(['email']);

        $this->assertGuest();
    }

    public function test_user_without_permission_receives_http_403(): void
    {
        $staff = User::factory()->create();
        $staff->assignRole(Roles::STAFF);

        $this->actingAs($staff)
            ->get('/admin/users')
            ->assertForbidden();

        $this->actingAs($staff)
            ->get('/admin/customers')
            ->assertOk();
    }

    public function test_super_admin_can_create_a_customer_and_the_change_is_logged(): void
    {
        $admin = User::query()->where('email', 'admin@dekorasi.local')->firstOrFail();

        Livewire::actingAs($admin)
            ->test(CreateCustomer::class)
            ->fillForm([
                'name' => 'Klien Uji',
                'type' => CustomerType::Company->value,
                'phone' => '08123456789',
                'email' => 'klien@uji.test',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $customer = Customer::query()->where('email', 'klien@uji.test')->first();

        $this->assertNotNull($customer);
        $this->assertSame($admin->company_id, $customer->company_id);
        $this->assertDatabaseHas('activity_log', [
            'subject_type' => Customer::class,
            'subject_id' => $customer->id,
            'event' => 'created',
        ]);
    }

    public function test_category_with_children_cannot_be_deleted(): void
    {
        $parent = Category::factory()->create(['name' => 'Induk']);
        Category::factory()->create([
            'name' => 'Anak',
            'parent_id' => $parent->id,
        ]);

        $warehouseUser = User::factory()->create();
        $warehouseUser->assignRole(Roles::WAREHOUSE);

        $this->assertFalse($warehouseUser->can('delete', $parent));

        $this->expectException(ValidationException::class);
        $parent->delete();
    }

    public function test_category_children_can_be_created_from_the_relation_manager(): void
    {
        $admin = User::query()->where('email', 'admin@dekorasi.local')->firstOrFail();
        $parent = Category::factory()->create(['name' => 'Dekorasi Uji']);

        Livewire::actingAs($admin)
            ->test(ViewCategory::class, ['record' => $parent->getRouteKey()])
            ->assertSee('Subkategori')
            ->assertSee('Log aktivitas')
            ->assertSeeLivewire(ChildrenRelationManager::class);

        Livewire::actingAs($admin)
            ->test(ChildrenRelationManager::class, [
                'ownerRecord' => $parent,
                'pageClass' => EditCategory::class,
            ])
            ->callTableAction('create', data: [
                'name' => 'Backdrop Uji',
                'is_active' => true,
            ])
            ->assertHasNoTableActionErrors();

        $child = Category::query()->where('name', 'Backdrop Uji')->first();

        $this->assertNotNull($child);
        $this->assertSame($parent->id, $child->parent_id);
        $this->assertTrue($parent->fresh()->children()->whereKey($child->id)->exists());
    }

    public function test_record_activity_relation_manager_follows_the_activity_permission(): void
    {
        $admin = User::query()->where('email', 'admin@dekorasi.local')->firstOrFail();
        $customer = Customer::factory()->create(['name' => 'Klien Relasi']);
        $staff = User::factory()->create();
        $staff->assignRole(Roles::STAFF);

        $this->actingAs($admin);
        $this->assertTrue(ActivitiesRelationManager::canViewForRecord($customer, ViewCustomer::class));

        $this->actingAs($staff);
        $this->assertFalse(ActivitiesRelationManager::canViewForRecord($customer, ViewCustomer::class));

        Livewire::actingAs($admin)
            ->test(ActivitiesRelationManager::class, [
                'ownerRecord' => $customer,
                'pageClass' => ViewCustomer::class,
            ])
            ->assertCanSeeTableRecords($customer->activities);
    }

    public function test_slug_and_warehouse_code_are_normalized(): void
    {
        $category = Category::query()->create([
            'name' => 'Tenda Lipat',
            'is_active' => true,
        ]);

        $warehouse = Warehouse::factory()->create([
            'code' => 'gd-baru',
        ]);

        $this->assertSame('tenda-lipat', $category->slug);
        $this->assertSame('GD-BARU', $warehouse->fresh()->code);
    }

    public function test_seeder_can_run_again_without_duplicating_reference_data(): void
    {
        $this->seed();

        $this->assertSame(7, SpatieRole::query()->count());
        $this->assertSame(1, Company::query()->count());
        $this->assertSame(1, User::query()->where('email', 'admin@dekorasi.local')->count());
        $this->assertSame(1, Category::query()->where('slug', 'dekorasi')->count());
        $this->assertSame(1, Warehouse::query()->where('code', 'GD-UTAMA')->count());
    }
}
