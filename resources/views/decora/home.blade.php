@extends('decora.portal.layout')

@section('content')
    <section class="hero" aria-labelledby="home-title">
        <img class="hero-image" src="{{ asset('images/decora/stage-setup.webp') }}" alt="" fetchpriority="high">
        <div class="hero-content">
            <p class="kicker">Portal internal</p>
            <h1 id="home-title">Siapkan acara.<br>Satukan tim.</h1>
            <p>Kelola proyek dekorasi, stok barang, dan persiapan acara dari satu tempat.</p>
            <a class="entry-link" href="{{ route('filament.admin.pages.dashboard') }}">
                Masuk ke ERP <span aria-hidden="true">→</span>
            </a>
        </div>
    </section>

    <section class="workspaces" aria-labelledby="workspace-title">
        <h2 id="workspace-title">Ruang kerja DECORA</h2>
        <div class="workspace-grid">
            <article>
                <a class="workspace-card" href="{{ route('decora.portal.projects') }}">
                    <span class="card-photo"><img src="{{ asset('images/decora/project-planning.webp') }}" alt="" width="457" height="364" loading="lazy"></span>
                    <h3>Proyek Dekorasi</h3>
                    <p>Susun kebutuhan dan jadwal setiap acara.</p>
                    <span class="card-go">Buka ruang kerja <span aria-hidden="true">→</span></span>
                </a>
            </article>
            <article>
                <a class="workspace-card" href="{{ route('decora.portal.inventory') }}">
                    <span class="card-photo"><img src="{{ asset('images/decora/warehouse.webp') }}" alt="" width="446" height="364" loading="lazy"></span>
                    <h3>Gudang &amp; Inventaris</h3>
                    <p>Kelola barang dan persiapan perlengkapan.</p>
                    <span class="card-go">Buka ruang kerja <span aria-hidden="true">→</span></span>
                </a>
            </article>
            <article>
                <a class="workspace-card" href="{{ route('decora.portal.production') }}">
                    <span class="card-photo"><img src="{{ asset('images/decora/production-team.webp') }}" alt="" width="457" height="364" loading="lazy"></span>
                    <h3>Tim &amp; Produksi</h3>
                    <p>Koordinasikan tugas saat persiapan acara.</p>
                    <span class="card-go">Buka ruang kerja <span aria-hidden="true">→</span></span>
                </a>
            </article>
        </div>
    </section>
    <section class="workflow" id="alur-kerja" aria-labelledby="workflow-title">
        <div class="workflow-intro">
            <h2 id="workflow-title">Dari rencana ke hari acara</h2>
            <p>Tiga tahap persiapan yang menghubungkan tim proyek, gudang, dan produksi.</p>
            <a class="workflow-link" href="{{ route('decora.portal.workflow') }}">Lihat alur kerja <span aria-hidden="true">→</span></a>
        </div>
        <ol class="workflow-steps">
            <li>
                <h3>Rencanakan kebutuhan</h3>
                <p>Catat tanggal, lokasi, konsep dekorasi, dan perlengkapan yang dibutuhkan.</p>
            </li>
            <li>
                <h3>Siapkan perlengkapan</h3>
                <p>Cocokkan daftar kebutuhan dengan barang yang siap dibawa ke lokasi.</p>
            </li>
            <li>
                <h3>Koordinasikan hari acara</h3>
                <p>Sepakati pembagian tugas dan urutan pemasangan sebelum tim berangkat.</p>
            </li>
        </ol>
    </section>

    <section class="access-info" aria-labelledby="access-title">
        <div>
            <h2 id="access-title">Akses untuk tim DECORA</h2>
            <p>Gunakan akun internal perusahaan. Jika belum punya akun atau tidak bisa membuka modul yang dibutuhkan, hubungi admin DECORA.</p>
        </div>
        <a class="entry-link" href="{{ route('filament.admin.pages.dashboard') }}">
            Masuk ke ERP <span aria-hidden="true">→</span>
        </a>
    </section>
@endsection
