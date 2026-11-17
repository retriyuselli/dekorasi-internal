@extends('decora.portal.layout')

@section('title', 'Tim & Produksi')

@section('content')
    <section class="detail-hero" aria-labelledby="page-title">
        <div class="detail-intro">
            <p class="kicker">Modul produksi</p>
            <h1 id="page-title">Tim &amp; Produksi</h1>
            <p>Satu tempat untuk membagi tugas, menyusun urutan pemasangan, dan menutup serah terima di lokasi.</p>
            <div class="hero-actions">
                <a class="entry-link" href="#contoh-tampilan">Lihat contoh tampilan <span aria-hidden="true">→</span></a>
                <a class="ghost-link" href="{{ route('decora.portal.workflow') }}">Lihat alur kerja</a>
            </div>
        </div>
        <img src="{{ asset('images/decora/production-team.webp') }}" alt="" width="457" height="364" fetchpriority="high">
    </section>

    <section class="project-block" aria-labelledby="role-title">
        <h2 id="role-title">Peran di lokasi</h2>
        <div class="kind-grid">
            <article>
                <h3>Bongkar muat</h3>
                <p>Menerima barang dari mobil dan meletakkannya di area yang sudah disepakati.</p>
            </article>
            <article>
                <h3>Pemasangan</h3>
                <p>Mengerjakan backdrop, pelaminan, panggung, dan detail sesuai urutan venue.</p>
            </article>
            <article>
                <h3>Pemeriksaan</h3>
                <p>Mencocokkan hasil pasang dengan daftar area sebelum tamu datang.</p>
            </article>
            <article>
                <h3>Serah terima</h3>
                <p>Menutup pekerjaan bersama penanggung jawab acara dan mencatat yang perlu ditindaklanjuti.</p>
            </article>
        </div>
    </section>

    <section class="project-block" aria-labelledby="field-title">
        <h2 id="field-title">Persiapan tim di lapangan</h2>
        <ol class="project-stages cols-4">
            <li>
                <span>01</span>
                <h3>Bagi tugas</h3>
                <p>Tentukan penanggung jawab bongkar muat, pemasangan, dan pemeriksaan akhir.</p>
            </li>
            <li>
                <span>02</span>
                <h3>Susun urutan</h3>
                <p>Pasang area yang menghalangi akses lebih dulu, lalu backdrop dan detail.</p>
            </li>
            <li>
                <span>03</span>
                <h3>Sesuaikan jam venue</h3>
                <p>Cocokkan kedatangan tim dengan izin masuk dan jam mulai acara.</p>
            </li>
            <li>
                <span>04</span>
                <h3>Periksa akhir</h3>
                <p>Cek tiap area bersama penanggung jawab sebelum serah terima.</p>
            </li>
        </ol>
    </section>

    <section class="project-block" id="contoh-tampilan" aria-labelledby="sample-title">
        <div class="block-head">
            <h2 id="sample-title">Contoh tampilan</h2>
            <p>Ilustrasi antarmuka untuk materi promosi. Bukan penugasan kru yang tersimpan.</p>
        </div>
        <div class="sample-grid">
            <article class="sample-card">
                <p class="sample-status prep">Persiapan</p>
                <h3>Tim muat</h3>
                <p>Gudang Utama ke Royal Ambarrukmo</p>
                <dl>
                    <div><dt>Jam</dt><dd>07.00</dd></div>
                    <div><dt>Tugas</dt><dd>Bongkar rangka dan kain drapery</dd></div>
                </dl>
            </article>
            <article class="sample-card">
                <p class="sample-status prod">Produksi</p>
                <h3>Tim backdrop</h3>
                <p>Pernikahan Rina &amp; Andika</p>
                <dl>
                    <div><dt>Jam</dt><dd>09.00</dd></div>
                    <div><dt>Tugas</dt><dd>Pasang backdrop 3×6 m di pintu masuk</dd></div>
                </dl>
            </article>
            <article class="sample-card">
                <p class="sample-status survey">Pemeriksaan</p>
                <h3>Tim serah terima</h3>
                <p>Royal Ambarrukmo, Yogyakarta</p>
                <dl>
                    <div><dt>Jam</dt><dd>15.00</dd></div>
                    <div><dt>Tugas</dt><dd>Cek pelaminan, bunga, dan jalur tamu</dd></div>
                </dl>
            </article>
        </div>
    </section>

    <section class="project-sheet" aria-labelledby="sheet-title">
        <div>
            <p class="kicker sheet-kicker">Contoh urutan pemasangan</p>
            <h2 id="sheet-title">Pernikahan Rina &amp; Andika</h2>
            <p>Begitulah satu hari lapangan dibaca tim: siapa datang, area mana yang dikerjakan, dan kapan pekerjaan ditutup.</p>
        </div>
        <div class="sheet-grid">
            <article>
                <h3>Pagi</h3>
                <ul>
                    <li>Tim muat tiba di venue</li>
                    <li>Barang diletakkan di area loading</li>
                    <li>Akses gedung sudah dibuka</li>
                </ul>
            </article>
            <article>
                <h3>Siang</h3>
                <ul>
                    <li>Backdrop pintu masuk</li>
                    <li>Pelaminan dan jalan pengantin</li>
                    <li>Sudut foto dan lighting</li>
                </ul>
            </article>
            <article>
                <h3>Sore</h3>
                <ul>
                    <li>Pemeriksaan tiap area</li>
                    <li>Serah terima ke penanggung jawab</li>
                    <li>Catatan untuk barang yang kurang</li>
                </ul>
            </article>
            <article>
                <h3>Sesudah acara</h3>
                <ul>
                    <li>Bongkar sesuai urutan kebalikan</li>
                    <li>Muat kembali ke gudang</li>
                    <li>Pisahkan barang yang perlu dicek</li>
                </ul>
            </article>
        </div>
    </section>

    <div class="project-split">
        <section class="project-panel" aria-labelledby="depart-title">
            <h2 id="depart-title">Sebelum tim berangkat</h2>
            <ul class="project-notes">
                <li>Nama penanggung jawab di lokasi</li>
                <li>Jam muat, keberangkatan, dan akses venue</li>
                <li>Urutan pemasangan tiap area</li>
                <li>Kontak yang dihubungi bila barang kurang</li>
                <li>Daftar yang harus dicek sebelum serah terima</li>
            </ul>
        </section>
        <section class="project-panel" aria-labelledby="crew-title">
            <h2 id="crew-title">Yang tercatat di satu tugas</h2>
            <p>Ini contoh isi kartu tugas. Bukan daftar kru yang tersimpan.</p>
            <ul class="supply-list">
                <li>Nama tim dan perannya</li>
                <li>Area yang dikerjakan</li>
                <li>Jam mulai dan jam selesai</li>
                <li>Barang yang harus sudah ada di lokasi</li>
                <li>Status persiapan, produksi, atau pemeriksaan</li>
            </ul>
        </section>
    </div>

    <section class="project-block" aria-labelledby="next-title">
        <h2 id="next-title">Lanjut ke bagian lain</h2>
        <div class="project-links">
            <a href="{{ route('decora.portal.projects') }}">
                <img src="{{ asset('images/decora/project-planning.webp') }}" alt="" width="457" height="364" loading="lazy">
                <h3>Proyek Dekorasi</h3>
                <p>Ambil tanggal, lokasi, dan area yang harus dipasang.</p>
            </a>
            <a href="{{ route('decora.portal.inventory') }}">
                <img src="{{ asset('images/decora/warehouse.webp') }}" alt="" width="446" height="364" loading="lazy">
                <h3>Gudang &amp; Inventaris</h3>
                <p>Pastikan perlengkapan sudah disiapkan sebelum tim berangkat.</p>
            </a>
        </div>
    </section>
@endsection
