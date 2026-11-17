@extends('decora.layout')

@section('title', 'Dashboard')
@section('nav-dashboard', 'active')

@section('content')
    <div class="dashboard">
        <header class="dashboard-hero">
            <div>
                <time datetime="{{ now()->format('Y-m') }}">{{ now()->locale('id')->translatedFormat('F Y') }}</time>
                <h1>Selamat Datang di DECORA ERP</h1>
                <p>Pantau proyek, pendapatan, dan inventaris dari satu dasbor.</p>
            </div>
            <a class="hero-link" href="{{ route('decora.inventory') }}">
                @include('decora.icon', ['name' => 'box'])
                Buka inventaris <span aria-hidden="true">↗</span>
            </a>
        </header>

        <section class="kpis" aria-label="Ringkasan operasional">
            <article class="kpi">
                <div class="kpi-top"><span class="kpi-icon">@include('decora.icon', ['name' => 'folder'])</span><p>Proyek Aktif</p></div>
                <strong>—</strong>
                <span>{{ $modules['projects'] ? 'Tabel proyek ada, tetapi jadwal belum dihubungkan.' : 'Modul proyek belum tersedia' }}</span>
            </article>
            <article class="kpi">
                <div class="kpi-top"><span class="kpi-icon">@include('decora.icon', ['name' => 'invoice'])</span><p>Penawaran Berjalan</p></div>
                <strong>—</strong>
                <span>{{ $modules['quotations'] ? 'Tabel penawaran ada, tetapi status belum dihubungkan.' : 'Modul penawaran belum tersedia' }}</span>
            </article>
            <article class="kpi">
                <div class="kpi-top"><span class="kpi-icon">@include('decora.icon', ['name' => 'chart'])</span><p>Pendapatan Bulan Ini</p></div>
                <strong>—</strong>
                <span>{{ $modules['revenue'] ? 'Tabel keuangan ada, tetapi nominal belum dihubungkan.' : 'Modul keuangan belum tersedia' }}</span>
            </article>
            <article class="kpi">
                <div class="kpi-top"><span class="kpi-icon">@include('decora.icon', ['name' => 'box'])</span><p>Stok Tersedia</p></div>
                <strong>—</strong>
                <span>{{ $modules['inventory'] ? 'Tabel barang ada, tetapi jumlah stok belum dihubungkan.' : 'Modul inventaris belum tersedia' }}</span>
            </article>
        </section>

        <div class="board">
            <section class="card chart-card">
                <div class="card-head">
                    <h2>Pendapatan Bulanan</h2>
                    <span class="chip">Tahun {{ now()->year }}</span>
                </div>
                <div class="plot">
                    <div class="dashboard-empty">
                        <span class="kpi-icon">@include('decora.icon', ['name' => 'chart'])</span>
                        <p class="empty-title">Belum ada data pendapatan</p>
                        <p>Pembayaran yang tercatat akan tampil di grafik ini.</p>
                    </div>
                </div>
                <div class="legend">
                    <span><i class="swatch real"></i> Realisasi Pendapatan</span>
                    <span><i class="swatch target"></i> Target Pendapatan</span>
                </div>
            </section>
            <section class="card">
                <div class="card-head">
                    <h2>Jadwal Event Mendatang</h2>
                    <a href="{{ route('decora.module', 'proyek') }}">Lihat proyek <span aria-hidden="true">↗</span></a>
                </div>
                <div class="dashboard-empty">
                    <span class="kpi-icon">@include('decora.icon', ['name' => 'folder'])</span>
                    <p class="empty-title">Jadwal belum tersedia</p>
                    <p>Event akan tampil setelah jadwal proyek tercatat.</p>
                </div>
            </section>
        </div>

        <div class="board">
            <section class="card">
                <div class="card-head">
                    <h2>Peringatan Inventory</h2>
                    <a href="{{ route('decora.inventory') }}">Lihat inventaris <span aria-hidden="true">↗</span></a>
                </div>
                <div class="dashboard-empty">
                    <span class="kpi-icon">@include('decora.icon', ['name' => 'box'])</span>
                    <p class="empty-title">Peringatan stok belum tersedia</p>
                    <p>Peringatan muncul setelah jumlah dan batas minimum stok tercatat.</p>
                </div>
                <p class="warehouse-count">{{ $warehouseCount }} lokasi gudang tercatat</p>
            </section>
            <section class="card">
                <div class="card-head">
                    <h2>Aktivitas Terbaru</h2>
                    <a href="{{ url('/admin/log-aktivitas') }}">Lihat semua</a>
                </div>
                @if ($activities->isEmpty())
                    <p class="empty-title">Belum ada aktivitas</p>
                    <p>Perubahan pelanggan, vendor, kategori, dan gudang akan tampil di sini.</p>
                @else
                    <ul class="feed">
                        @foreach ($activities as $activity)
                            <li>
                                <span class="dot" aria-hidden="true"></span>
                                <div>
                                    <strong>{{ $activity['label'] }} — {{ $activity['subject'] }}</strong>
                                    <span>Oleh {{ $activity['actor'] }} · {{ $activity['time'] }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>
    </div>
@endsection
