@extends('decora.layout')

@section('title', 'Gudang & Inventaris')
@section('nav-inventory', 'active')

@section('content')
    <div class="page-head">
        <div>
            <h1>Gudang & Inventaris</h1>
            <p class="lede">Lokasi gudang sudah tercatat. Jumlah barang menunggu tabel inventaris.</p>
        </div>
        <form method="post" action="{{ route('decora.inventory.store') }}">
            @csrf
            <button class="primary" type="submit">Tambah barang</button>
        </form>
    </div>

    @if (session('status'))
        <p class="banner" role="status">{{ session('status') }}</p>
    @endif

    <section class="kpis" aria-label="Ringkasan inventaris">
        @foreach ([
            'Total inventaris' => 'Modul inventaris belum tersedia',
            'Tersedia' => 'Belum ada jumlah barang tersedia',
            'Dialokasikan' => 'Belum ada alokasi ke proyek',
            'Dalam perbaikan' => 'Belum ada barang dalam perbaikan',
        ] as $label => $note)
            <article>
                <p>{{ $label }}</p>
                <strong>—</strong>
                <span>{{ $moduleReady ? 'Tabel ada, tetapi rincian stok belum dihubungkan' : $note }}</span>
            </article>
        @endforeach
    </section>
    <p class="note">{{ $warehouseCount }} lokasi gudang sudah tercatat. Angka di kartu ini bukan jumlah stok.</p>

    <div class="split">
        <section class="card">
            <h2>Distribusi inventaris</h2>
            <p>Perbandingan barang menurut kategori atau gudang.</p>
            <p class="empty-title">Grafik belum tersedia</p>
            <p>Distribusi ditampilkan setelah barang dan jumlahnya tersimpan.</p>
        </section>
        <section class="card">
            <h2>Alokasi berdasarkan proyek</h2>
            <p>Barang yang sedang dipakai sebuah acara.</p>
            <p class="empty-title">Belum ada alokasi</p>
            <p>Alokasi muncul setelah modul proyek dan inventaris terhubung.</p>
        </section>
    </div>

    <section class="card">
        <h2>Daftar barang</h2>
        <p>Pencarian dan filter memakai data kategori serta gudang yang sudah ada. Hasil barang tetap kosong.</p>
        <form class="filters" method="get" action="{{ route('decora.inventory') }}">
            <label>
                <span>Cari</span>
                <input type="search" name="q" value="{{ $filters['q'] }}" placeholder="Nama barang" maxlength="100">
            </label>
            <label>
                <span>Kategori</span>
                <select name="kategori">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $id => $name)
                        <option value="{{ $id }}" @selected($filters['kategori'] === (string) $id)>{{ $name }}</option>
                    @endforeach
                </select>
            </label>
            <label>
                <span>Gudang</span>
                <select name="gudang">
                    <option value="">Semua gudang</option>
                    @foreach ($warehouses as $id => $name)
                        <option value="{{ $id }}" @selected($filters['gudang'] === (string) $id)>{{ $name }}</option>
                    @endforeach
                </select>
            </label>
            <label>
                <span>Status</span>
                <select name="status">
                    <option value="">Semua status</option>
                    <option value="tersedia" @selected($filters['status'] === 'tersedia')>Tersedia</option>
                    <option value="dialokasikan" @selected($filters['status'] === 'dialokasikan')>Dialokasikan</option>
                    <option value="perbaikan" @selected($filters['status'] === 'perbaikan')>Dalam perbaikan</option>
                </select>
            </label>
            <button type="submit">Terapkan filter</button>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Kategori</th>
                        <th>Gudang</th>
                        <th>Status</th>
                        <th>Stok</th>
                        <th>Minimum</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6">
                            <p class="empty-title">Tidak ada barang</p>
                            <p>Filter tidak menghasilkan data palsu. Penambahan barang menyimpan data setelah modul inventaris tersedia.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row-actions">
            <button type="button" disabled>Detail</button>
            <button type="button" disabled>Edit</button>
            <button type="button" disabled>Hapus</button>
            <span>Edit, Detail, dan Hapus aktif pada tiap barang setelah ada data.</span>
        </div>
        <p class="note">Indikator stok minimum dan peringatan restock menunggu jumlah barang.</p>
    </section>
@endsection
