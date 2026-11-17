@extends('decora.portal.layout')

@section('title', 'Gudang & Inventaris')

@section('content')
    <section class="detail-hero" aria-labelledby="page-title">
        <div class="detail-intro">
            <p class="kicker">Modul inventaris</p>
            <h1 id="page-title">Gudang &amp; Inventaris</h1>
            <p>Satu tempat untuk melihat barang yang siap, yang sedang dipakai acara, dan yang perlu diperiksa.</p>
            <div class="hero-actions">
                <a class="entry-link" href="#contoh-tampilan">Lihat contoh tampilan <span aria-hidden="true">→</span></a>
                <a class="ghost-link" href="{{ route('decora.portal.projects') }}">Lihat persiapan proyek</a>
            </div>
        </div>
        <img src="{{ asset('images/decora/warehouse.webp') }}" alt="" width="446" height="364" fetchpriority="high">
    </section>

    <section class="project-block" aria-labelledby="state-title">
        <h2 id="state-title">Kondisi barang</h2>
        <div class="kind-grid">
            <article>
                <h3>Tersedia</h3>
                <p>Barang berada di gudang dan siap dibawa ke acara berikutnya.</p>
            </article>
            <article>
                <h3>Dialokasikan</h3>
                <p>Barang sudah diikat ke sebuah proyek dan tidak dihitung sebagai stok bebas.</p>
            </article>
            <article>
                <h3>Dalam perbaikan</h3>
                <p>Barang dipisahkan sampai kondisinya layak dipakai kembali.</p>
            </article>
            <article>
                <h3>Menyentuh minimum</h3>
                <p>Jumlah yang tersisa mendekati batas, jadi tim bisa menyiapkan pengganti lebih awal.</p>
            </article>
        </div>
    </section>

    <section class="project-block" aria-labelledby="check-title">
        <h2 id="check-title">Pemeriksaan perlengkapan</h2>
        <ol class="project-stages cols-4">
            <li>
                <span>01</span>
                <h3>Terima kebutuhan</h3>
                <p>Kelompokkan permintaan proyek menurut kategori dan lokasi acara.</p>
            </li>
            <li>
                <span>02</span>
                <h3>Cek kondisi</h3>
                <p>Periksa jumlah dan kondisi fisik sebelum barang dimuat.</p>
            </li>
            <li>
                <span>03</span>
                <h3>Siapkan muatan</h3>
                <p>Pisahkan barang yang berangkat dari barang yang tetap di gudang.</p>
            </li>
            <li>
                <span>04</span>
                <h3>Catat kembali</h3>
                <p>Cocokkan perlengkapan yang pulang dan tandai yang perlu diperiksa.</p>
            </li>
        </ol>
    </section>

    <section class="project-block" id="contoh-tampilan" aria-labelledby="sample-title">
        <div class="block-head">
            <h2 id="sample-title">Contoh tampilan</h2>
            <p>Ilustrasi antarmuka untuk materi promosi. Bukan jumlah stok yang tersimpan.</p>
        </div>
        <div class="sample-grid">
            <article class="sample-card">
                <p class="sample-status ready">Tersedia</p>
                <h3>Kursi Tiffany</h3>
                <p>Gudang Utama</p>
                <dl>
                    <div><dt>Jumlah</dt><dd>48 pcs</dd></div>
                    <div><dt>Minimum</dt><dd>20 pcs</dd></div>
                </dl>
            </article>
            <article class="sample-card">
                <p class="sample-status allocated">Dialokasikan</p>
                <h3>Backdrop Frame 3×6 m</h3>
                <p>Gudang Utama</p>
                <dl>
                    <div><dt>Jumlah</dt><dd>2 set berangkat</dd></div>
                    <div><dt>Dipakai</dt><dd>Pernikahan Rina &amp; Andika</dd></div>
                </dl>
            </article>
            <article class="sample-card">
                <p class="sample-status repair">Dalam perbaikan</p>
                <h3>Standing Lamp</h3>
                <p>Gudang Lighting</p>
                <dl>
                    <div><dt>Jumlah</dt><dd>4 pcs</dd></div>
                    <div><dt>Catatan</dt><dd>Menunggu pengecekan sebelum dipakai lagi</dd></div>
                </dl>
            </article>
        </div>
    </section>

    <section class="project-sheet" aria-labelledby="sheet-title">
        <div>
            <p class="kicker sheet-kicker">Contoh kartu barang</p>
            <h2 id="sheet-title">Backdrop Frame 3×6 m</h2>
            <p>Begitulah satu barang dibaca tim gudang: lokasi, pemakaian, dan batas minimum dalam satu kartu.</p>
        </div>
        <div class="sheet-grid">
            <article>
                <h3>Identitas</h3>
                <p>Rangka backdrop untuk pintu masuk. Satuan yang dipakai adalah set.</p>
            </article>
            <article>
                <h3>Lokasi</h3>
                <ul>
                    <li>Gudang Utama</li>
                    <li>Rak rangka besar</li>
                    <li>Mudah dimuat ke mobil acara</li>
                </ul>
            </article>
            <article>
                <h3>Pemakaian</h3>
                <ul>
                    <li>2 set untuk Pernikahan Rina &amp; Andika</li>
                    <li>3 set tetap di gudang</li>
                    <li>Tidak dihitung sebagai stok bebas</li>
                </ul>
            </article>
            <article>
                <h3>Perhatian</h3>
                <ul>
                    <li>Batas minimum 2 set</li>
                    <li>Siapkan cadangan sebelum hari-H</li>
                    <li>Cek rangka saat barang kembali</li>
                </ul>
            </article>
        </div>
    </section>

    <div class="project-split">
        <section class="project-panel" aria-labelledby="notes-title">
            <h2 id="notes-title">Yang tercatat di satu barang</h2>
            <ul class="project-notes">
                <li>Nama barang dan kelompoknya</li>
                <li>Gudang tempat barang disimpan</li>
                <li>Satuan, jumlah, dan batas minimum</li>
                <li>Status tersedia, dialokasikan, atau perbaikan</li>
                <li>Acara yang sedang memakai barang itu</li>
            </ul>
        </section>
        <section class="project-panel" aria-labelledby="place-title">
            <h2 id="place-title">Contoh lokasi gudang</h2>
            <p>Nama tempat ini ilustrasi. Bukan daftar gudang yang tersimpan.</p>
            <ul class="supply-list">
                <li>Gudang Utama</li>
                <li>Gudang Tenda</li>
                <li>Gudang Bunga</li>
                <li>Gudang Lighting</li>
                <li>Gudang Kain</li>
                <li>Gudang Panggung</li>
            </ul>
        </section>
    </div>

    <section class="project-block" aria-labelledby="next-title">
        <h2 id="next-title">Lanjut ke bagian lain</h2>
        <div class="project-links">
            <a href="{{ route('decora.portal.projects') }}">
                <img src="{{ asset('images/decora/project-planning.webp') }}" alt="" width="457" height="364" loading="lazy">
                <h3>Proyek Dekorasi</h3>
                <p>Lihat kebutuhan acara sebelum barang disiapkan.</p>
            </a>
            <a href="{{ route('decora.portal.production') }}">
                <img src="{{ asset('images/decora/production-team.webp') }}" alt="" width="457" height="364" loading="lazy">
                <h3>Tim &amp; Produksi</h3>
                <p>Sambungkan barang yang dimuat dengan jadwal pemasangan.</p>
            </a>
        </div>
    </section>
@endsection
