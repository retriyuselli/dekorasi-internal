@extends('decora.portal.layout')

@section('title', 'Alur Kerja')

@section('content')
    <section class="detail-hero" aria-labelledby="page-title">
        <div class="detail-intro">
            <p class="kicker">Cara kerja DECORA</p>
            <h1 id="page-title">Alur Kerja</h1>
            <p>Empat tahap yang menghubungkan rencana acara, perlengkapan, pemasangan, dan pengembalian barang.</p>
            <div class="hero-actions">
                <a class="entry-link" href="#contoh-tampilan">Lihat contoh tampilan <span aria-hidden="true">→</span></a>
                <a class="ghost-link" href="{{ route('decora.portal.projects') }}">Mulai dari proyek</a>
            </div>
        </div>
        <img src="{{ asset('images/decora/stage-setup.webp') }}" alt="" width="1536" height="1024" fetchpriority="high">
    </section>

    <section class="project-block" aria-labelledby="flow-title">
        <h2 id="flow-title">Tahapan persiapan acara</h2>
        <ol class="flow-steps">
            <li>
                <a href="{{ route('decora.portal.projects') }}">
                    <span>01</span>
                    <div>
                        <h3>Rencanakan proyek</h3>
                        <p>Sepakati konsep, kebutuhan dekorasi, tenggat, dan penanggung jawab.</p>
                        <span class="card-go">Buka proyek <span aria-hidden="true">→</span></span>
                    </div>
                </a>
            </li>
            <li>
                <a href="{{ route('decora.portal.inventory') }}">
                    <span>02</span>
                    <div>
                        <h3>Siapkan perlengkapan</h3>
                        <p>Cocokkan daftar barang dengan kebutuhan dan jadwal pengiriman.</p>
                        <span class="card-go">Buka inventaris <span aria-hidden="true">→</span></span>
                    </div>
                </a>
            </li>
            <li>
                <a href="{{ route('decora.portal.production') }}">
                    <span>03</span>
                    <div>
                        <h3>Laksanakan pemasangan</h3>
                        <p>Koordinasikan kedatangan kru, pemasangan, dan pemeriksaan hasil.</p>
                        <span class="card-go">Buka produksi <span aria-hidden="true">→</span></span>
                    </div>
                </a>
            </li>
            <li>
                <a href="{{ route('decora.portal.inventory') }}">
                    <span>04</span>
                    <div>
                        <h3>Tutup pekerjaan</h3>
                        <p>Cek barang yang kembali dan catat hal yang perlu ditindaklanjuti.</p>
                        <span class="card-go">Kembali ke gudang <span aria-hidden="true">→</span></span>
                    </div>
                </a>
            </li>
        </ol>
    </section>

    <section class="project-block" aria-labelledby="hand-title">
        <h2 id="hand-title">Yang berpindah antar tahap</h2>
        <div class="kind-grid">
            <article>
                <h3>Dari proyek</h3>
                <p>Tanggal, lokasi, konsep, dan daftar area yang harus dikerjakan.</p>
            </article>
            <article>
                <h3>Ke gudang</h3>
                <p>Kebutuhan barang, satuan, dan acara yang akan memakai perlengkapan itu.</p>
            </article>
            <article>
                <h3>Ke lapangan</h3>
                <p>Jam akses venue, urutan pemasangan, dan nama penanggung jawab.</p>
            </article>
            <article>
                <h3>Kembali ke gudang</h3>
                <p>Barang yang pulang utuh dan barang yang perlu diperiksa sebelum dipakai lagi.</p>
            </article>
        </div>
    </section>

    <section class="project-block" id="contoh-tampilan" aria-labelledby="sample-title">
        <div class="block-head">
            <h2 id="sample-title">Contoh tampilan</h2>
            <p>Ilustrasi antarmuka untuk materi promosi. Bukan progres acara yang tersimpan.</p>
        </div>
        <div class="sample-grid">
            <article class="sample-card">
                <p class="sample-status prep">Rencana</p>
                <h3>Pernikahan Rina &amp; Andika</h3>
                <p>Royal Ambarrukmo, Yogyakarta</p>
                <dl>
                    <div><dt>Tahap</dt><dd>Proyek sudah disusun</dd></div>
                    <div><dt>Berikutnya</dt><dd>Kunci perlengkapan</dd></div>
                </dl>
            </article>
            <article class="sample-card">
                <p class="sample-status allocated">Gudang</p>
                <h3>Backdrop Frame 3×6 m</h3>
                <p>2 set dialokasikan ke acara</p>
                <dl>
                    <div><dt>Tahap</dt><dd>Perlengkapan disiapkan</dd></div>
                    <div><dt>Berikutnya</dt><dd>Muat ke lokasi</dd></div>
                </dl>
            </article>
            <article class="sample-card">
                <p class="sample-status prod">Lapangan</p>
                <h3>Tim backdrop</h3>
                <p>Pemasangan pintu masuk</p>
                <dl>
                    <div><dt>Tahap</dt><dd>Produksi berjalan</dd></div>
                    <div><dt>Berikutnya</dt><dd>Pemeriksaan sore</dd></div>
                </dl>
            </article>
        </div>
    </section>

    <section class="project-sheet" aria-labelledby="sheet-title">
        <div>
            <p class="kicker sheet-kicker">Contoh satu acara</p>
            <h2 id="sheet-title">Dari brief sampai barang pulang</h2>
            <p>Satu pernikahan yang sama dibaca bergantian oleh tim proyek, gudang, dan produksi.</p>
        </div>
        <div class="sheet-grid">
            <article>
                <h3>Rencana</h3>
                <p>Resepsi malam, 300 tamu, nuansa hijau sage. Area utama: backdrop, pelaminan, dan sudut foto.</p>
            </article>
            <article>
                <h3>Perlengkapan</h3>
                <ul>
                    <li>Rangka backdrop 3×6 m</li>
                    <li>Set pelaminan</li>
                    <li>Lampu hangat</li>
                </ul>
            </article>
            <article>
                <h3>Pemasangan</h3>
                <ul>
                    <li>Muat pukul 07.00</li>
                    <li>Backdrop pukul 09.00</li>
                    <li>Serah terima pukul 15.00</li>
                </ul>
            </article>
            <article>
                <h3>Penutupan</h3>
                <ul>
                    <li>Barang utuh kembali ke gudang</li>
                    <li>Rangka dicek sebelum disimpan</li>
                    <li>Catatan diteruskan ke acara berikutnya</li>
                </ul>
            </article>
        </div>
    </section>

    <div class="project-split">
        <section class="project-panel" aria-labelledby="who-title">
            <h2 id="who-title">Siapa yang memakai tiap tahap</h2>
            <ul class="project-notes">
                <li>Tim proyek menyusun brief dan area</li>
                <li>Tim gudang menyiapkan dan menerima barang kembali</li>
                <li>Tim produksi memasang dan memeriksa hasil</li>
                <li>Penanggung jawab acara menutup serah terima</li>
            </ul>
        </section>
        <section class="project-panel" aria-labelledby="keep-title">
            <h2 id="keep-title">Yang tetap terlihat di setiap tahap</h2>
            <p>Supaya tim tidak mengulang pencatatan dari nol.</p>
            <ul class="supply-list">
                <li>Nama acara</li>
                <li>Tanggal dan lokasi</li>
                <li>Daftar area dekorasi</li>
                <li>Status tahap yang sedang berjalan</li>
            </ul>
        </section>
    </div>
@endsection
