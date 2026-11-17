<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_home_page_links_to_the_admin_panel(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('DECORA ERP');
        $response->assertSee('Siapkan acara.');
        $response->assertSee('Ruang kerja DECORA');
        $response->assertSee('Masuk ke ERP');
        $response->assertSee('href="'.url('/admin/login').'"', false);
        $response->assertSee('Login');
        $response->assertSee('href="'.route('filament.admin.pages.dashboard').'"', false);
        $response->assertDontSee('Aktivitas Terbaru');
    }

    public function test_the_project_page_is_a_static_promotion(): void
    {
        $this->get('/proyek')
            ->assertOk()
            ->assertSee('Proyek Dekorasi')
            ->assertSee('Contoh tampilan')
            ->assertSee('Pernikahan Rina &amp; Andika', false)
            ->assertSee('Bukan jadwal acara yang tersimpan')
            ->assertDontSee('master data')
            ->assertDontSee('Belum ada proyek tercatat');
    }

    public function test_the_inventory_page_is_a_static_promotion(): void
    {
        $this->get('/inventaris')
            ->assertOk()
            ->assertSee('Gudang &amp; Inventaris', false)
            ->assertSee('Contoh tampilan')
            ->assertSee('Bukan jumlah stok yang tersimpan')
            ->assertSee('Kursi Tiffany')
            ->assertDontSee('/gudang-inventaris')
            ->assertDontSee('Belum ada barang tercatat');
    }

    public function test_the_production_page_is_a_static_promotion(): void
    {
        $this->get('/produksi')
            ->assertOk()
            ->assertSee('Tim &amp; Produksi', false)
            ->assertSee('Contoh tampilan')
            ->assertSee('Bukan penugasan kru yang tersimpan')
            ->assertSee('Tim backdrop')
            ->assertDontSee('modul produksi menyimpan data')
            ->assertDontSee('/gudang-inventaris');
    }

    public function test_the_workflow_page_is_a_static_promotion(): void
    {
        $this->get('/alur-kerja')
            ->assertOk()
            ->assertSee('Alur Kerja')
            ->assertSee('Contoh tampilan')
            ->assertSee('Bukan progres acara yang tersimpan')
            ->assertSee('Dari brief sampai barang pulang')
            ->assertDontSee('/gudang-inventaris')
            ->assertDontSee('/dashboard');
    }
}
