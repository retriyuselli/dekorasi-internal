@extends('decora.layout')

@section('title', $title)

@section('content')
    <div class="welcome">
        <div>
            <h1>{{ $title }}</h1>
            <p>Modul ini belum tersedia. Data tidak ditampilkan sebelum tabel dan pencatatannya ada.</p>
        </div>
    </div>
    <section class="card">
        <p class="empty-title">Belum ada data</p>
        <p>Menu ini mengikuti peta ERP dekorasi. Isinya menyusul saat modul yang bersangkutan dibangun, tanpa angka contoh.</p>
        <p><a class="text-link" href="{{ route('decora.dashboard') }}">Kembali ke dashboard</a></p>
    </section>
@endsection
