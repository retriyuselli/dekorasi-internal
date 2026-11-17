@extends('decora.portal.layout')

@section('title', 'Proyek Dekorasi')

@section('content')
    <section class="detail-hero" aria-labelledby="page-title">
        <div class="detail-intro">
            <p class="kicker">Modul proyek</p>
            <h1 id="page-title">Proyek Dekorasi</h1>
            <p>Satu lembar untuk brief, area, perlengkapan, dan jadwal lapangan setiap acara.</p>
            <div class="hero-actions">
                <a class="entry-link" href="{{ route('decora.portal.workflow') }}">Lihat alur kerja <span aria-hidden="true">→</span></a>
                <a class="ghost-link" href="#contoh-tampilan">Lihat contoh tampilan</a>
            </div>
        </div>
        <img src="{{ asset('images/decora/project-planning.webp') }}" alt="" width="457" height="364" fetchpriority="high">
    </section>

    <section class="project-block" aria-labelledby="kind-title">
        <h2 id="kind-title">Acara yang ditangani</h2>
        <div class="kind-grid">
            <article>
                <h3>Pernikahan</h3>
                <p>Pelaminan, backdrop, bunga, dan jalur tamu disusun mengikuti denah venue.</p>
            </article>
            <article>
                <h3>Acara perusahaan</h3>
                <p>Panggung, lighting, dan signage disiapkan sesuai rundown dan jam akses gedung.</p>
            </article>
            <article>
                <h3>Gathering</h3>
                <p>Tenda, furnitur, dan area foto diatur untuk tamu yang datang bergelombang.</p>
            </article>
            <article>
                <h3>Pameran</h3>
                <p>Booth, display, dan pencahayaan mengikuti ukuran stan yang disewa.</p>
            </article>
        </div>
    </section>

    <section class="project-block" aria-labelledby="stage-title">
        <h2 id="stage-title">Jalannya sebuah proyek</h2>
        <ol class="project-stages">
            <li>
                <span>01</span>
                <h3>Terima brief</h3>
                <p>Catat tanggal, lokasi, jumlah tamu, dan konsep yang diminta klien.</p>
            </li>
            <li>
                <span>02</span>
                <h3>Susun area</h3>
                <p>Tentukan backdrop, pelaminan, panggung, dan jalur tamu sesuai kondisi venue.</p>
            </li>
            <li>
                <span>03</span>
                <h3>Kunci perlengkapan</h3>
                <p>Cocokkan kebutuhan dengan kelompok barang yang akan dibawa.</p>
            </li>
            <li>
                <span>04</span>
                <h3>Jadwalkan lapangan</h3>
                <p>Sepakati jam muat, pemasangan, dan serah terima sebelum tim berangkat.</p>
            </li>
            <li>
                <span>05</span>
                <h3>Tutup acara</h3>
                <p>Cek barang yang kembali dan catat hal yang perlu ditindaklanjuti.</p>
            </li>
        </ol>
    </section>

    <section class="project-block" id="contoh-tampilan" aria-labelledby="sample-title">
        <div class="block-head">
            <h2 id="sample-title">Contoh tampilan</h2>
            <p>Ilustrasi antarmuka untuk materi promosi. Bukan jadwal acara yang tersimpan.</p>
        </div>
        <div class="sample-grid">
            <article class="sample-card">
                <p class="sample-status prep">Persiapan</p>
                <h3>Pernikahan Rina &amp; Andika</h3>
                <p>Royal Ambarrukmo, Yogyakarta</p>
                <dl>
                    <div><dt>Tanggal</dt><dd>25 Januari</dd></div>
                    <div><dt>Area</dt><dd>Backdrop, pelaminan, bunga</dd></div>
                </dl>
            </article>
            <article class="sample-card">
                <p class="sample-status prod">Produksi</p>
                <h3>Corporate — PT Maju Bersama</h3>
                <p>Hotel Tentrem, Yogyakarta</p>
                <dl>
                    <div><dt>Tanggal</dt><dd>21 Januari</dd></div>
                    <div><dt>Area</dt><dd>Panggung, lighting, signage</dd></div>
                </dl>
            </article>
            <article class="sample-card">
                <p class="sample-status survey">Survey</p>
                <h3>Gathering — PT Sinar Abadi</h3>
                <p>Hyatt Regency, Yogyakarta</p>
                <dl>
                    <div><dt>Tanggal</dt><dd>30 Januari</dd></div>
                    <div><dt>Area</dt><dd>Tenda, furnitur, foto</dd></div>
                </dl>
            </article>
        </div>
    </section>

    <section class="project-sheet" aria-labelledby="sheet-title">
        <div>
            <p class="kicker sheet-kicker">Contoh lembar proyek</p>
            <h2 id="sheet-title">Pernikahan Rina &amp; Andika</h2>
            <p>Begitulah satu proyek dibaca tim: dari permintaan klien sampai urutan pemasangan.</p>
        </div>
        <div class="sheet-grid">
            <article>
                <h3>Brief</h3>
                <p>Resepsi malam, 300 tamu, nuansa hijau sage dan kain drapery. Penanggung jawab di lokasi: tim proyek.</p>
            </article>
            <article>
                <h3>Area</h3>
                <ul>
                    <li>Backdrop 3×6 m di pintu masuk</li>
                    <li>Pelaminan dan jalan pengantin</li>
                    <li>Sudut foto dengan bunga</li>
                </ul>
            </article>
            <article>
                <h3>Perlengkapan</h3>
                <ul>
                    <li>Kain drapery dan rangka backdrop</li>
                    <li>Set pelaminan</li>
                    <li>Lampu hangat dan standing lamp</li>
                </ul>
            </article>
            <article>
                <h3>Jadwal lapangan</h3>
                <ul>
                    <li>Survey venue sebelum hari-H</li>
                    <li>Muat barang pagi hari</li>
                    <li>Pemasangan selesai sebelum tamu datang</li>
                </ul>
            </article>
        </div>
    </section>

    <div class="project-split">
        <section class="project-panel" aria-labelledby="notes-title">
            <h2 id="notes-title">Yang tercatat di satu proyek</h2>
            <ul class="project-notes">
                <li>Nama acara dan penanggung jawab</li>
                <li>Tanggal pelaksanaan dan lokasi</li>
                <li>Konsep serta referensi visual</li>
                <li>Daftar area dekorasi</li>
                <li>Tenggat persetujuan desain dan pemasangan</li>
            </ul>
        </section>
        <section class="project-panel" aria-labelledby="supply-title">
            <h2 id="supply-title">Kelompok perlengkapan</h2>
            <p>Kelompok ini membantu tim menyusun kebutuhan, sebelum barang benar-benar dialokasikan.</p>
            <ul class="supply-list">
                <li>Backdrop</li>
                <li>Pelaminan</li>
                <li>Tenda</li>
                <li>Lighting</li>
                <li>Bunga</li>
                <li>Furnitur</li>
                <li>Panggung</li>
                <li>Signage</li>
            </ul>
        </section>
    </div>

    <section class="project-block" aria-labelledby="next-title">
        <h2 id="next-title">Lanjut ke bagian lain</h2>
        <div class="project-links">
            <a href="{{ route('decora.portal.inventory') }}">
                <img src="{{ asset('images/decora/warehouse.webp') }}" alt="" width="446" height="364" loading="lazy">
                <h3>Gudang &amp; Inventaris</h3>
                <p>Cocokkan perlengkapan sebelum barang dimuat.</p>
            </a>
            <a href="{{ route('decora.portal.production') }}">
                <img src="{{ asset('images/decora/production-team.webp') }}" alt="" width="457" height="364" loading="lazy">
                <h3>Tim &amp; Produksi</h3>
                <p>Atur tugas dan urutan pemasangan di lokasi.</p>
            </a>
        </div>
    </section>
@endsection
