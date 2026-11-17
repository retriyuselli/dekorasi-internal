<?php

namespace Tests\Feature;

use App\Enums\CustomerType;
use App\Enums\ProspectStatus;
use App\Filament\Resources\Prospects\Pages\CreateProspect;
use App\Filament\Resources\Prospects\ProspectResource;
use App\Models\Customer;
use App\Models\Prospect;
use App\Models\User;
use App\Modules\MasterData\Actions\ConvertProspectToCustomer;
use App\Support\Roles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProspectTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_seeded_prospects_exist_before_they_become_customers(): void
    {
        $this->assertSame(10, Prospect::query()->count());
        $this->assertSame(0, Prospect::query()->whereNotNull('customer_id')->count());
    }

    public function test_sales_can_record_a_prospect_and_staff_cannot_create_one(): void
    {
        $sales = User::query()->where('email', 'sales.dimas@dekorasi.local')->firstOrFail();
        $staff = User::factory()->create();
        $staff->assignRole(Roles::STAFF);

        $this->actingAs($staff)->get('/admin/prospects/create')->assertForbidden();

        Livewire::actingAs($sales)
            ->test(CreateProspect::class)
            ->fillForm([
                'name' => 'Calon Klien Uji',
                'type' => CustomerType::Individual->value,
                'phone' => '081200000111',
                'email' => 'calon@uji.test',
                'event_interest' => 'Pernikahan',
                'status' => ProspectStatus::New->value,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $prospect = Prospect::query()->where('email', 'calon@uji.test')->first();

        $this->assertNotNull($prospect);
        $this->assertNull($prospect->customer_id);
        $this->assertSame(ProspectStatus::New, $prospect->status);
        $this->assertDatabaseHas('activity_log', [
            'subject_type' => Prospect::class,
            'subject_id' => $prospect->id,
            'event' => 'created',
        ]);
    }

    public function test_converting_a_prospect_creates_one_customer_and_keeps_the_link(): void
    {
        $sales = User::query()->where('email', 'sales.dimas@dekorasi.local')->firstOrFail();
        $prospect = Prospect::factory()->create([
            'company_id' => $sales->company_id,
            'name' => 'Laras & Galih',
            'email' => 'laras.galih@uji.test',
            'event_interest' => 'Resepsi',
            'status' => ProspectStatus::Interested,
        ]);

        $this->actingAs($sales);

        $customer = app(ConvertProspectToCustomer::class)->execute($prospect);
        $again = app(ConvertProspectToCustomer::class)->execute($prospect->fresh());

        $this->assertTrue($customer->is($again));
        $this->assertSame(1, Customer::query()->where('email', 'laras.galih@uji.test')->count());
        $this->assertSame($customer->id, $prospect->fresh()->customer_id);
        $this->assertSame(ProspectStatus::Converted, $prospect->fresh()->status);
        $this->assertTrue($customer->prospect->is($prospect));
        $this->assertFalse(ProspectResource::canConvert($prospect->fresh()));
    }
}
