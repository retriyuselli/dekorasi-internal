<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DecoraUiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_dashboard_requires_login_and_uses_real_activity_and_empty_modules(): void
    {
        $this->get('/dashboard')->assertRedirect('/admin/login');

        $this->actingAs(User::query()->where('email', 'admin@dekorasi.local')->firstOrFail())
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('DECORA ERP')
            ->assertSee('ERP Bisnis Dekorasi')
            ->assertSee('Selamat Datang di DECORA ERP')
            ->assertSee('Proyek Aktif')
            ->assertSee('Penawaran Berjalan')
            ->assertSee('Pendapatan Bulan Ini')
            ->assertSee('Stok Tersedia')
            ->assertSee('Modul proyek belum tersedia')
            ->assertSee('Pendapatan Bulanan')
            ->assertSee('Jadwal Event Mendatang')
            ->assertSee('Peringatan Inventory')
            ->assertSee('Aktivitas Terbaru')
            ->assertSee('Dibuat')
            ->assertDontSee('Rp ');
    }

    public function test_public_inventory_page_stays_empty_without_saving(): void
    {
        $this->get('/gudang-inventaris')
            ->assertOk()
            ->assertSee('Total inventaris')
            ->assertSee('Modul inventaris belum tersedia')
            ->assertSee('Tidak ada barang')
            ->assertSee('Tambah barang')
            ->assertSee('Gudang Utama')
            ->assertSee('Dekorasi')
            ->assertDontSee('Rp ');

        $this->post('/gudang-inventaris')
            ->assertRedirect('/gudang-inventaris');

        $this->followRedirects($this->post('/gudang-inventaris'))
            ->assertSee('Modul barang belum tersedia. Data tidak disimpan.');

        $this->assertDatabaseMissing('migrations', [
            'migration' => 'create_inventory_items_table',
        ]);
    }

    public function test_filament_dashboard_is_unchanged(): void
    {
        $this->get('/admin')
            ->assertRedirect('/admin/login');

        $this->get('/admin/login')
            ->assertOk()
            ->assertDontSee('Modul proyek belum tersedia')
            ->assertDontSee('Gudang & Inventaris');
    }
}
