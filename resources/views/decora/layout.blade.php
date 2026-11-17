<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <title>@yield('title', 'Dasbor') — DECORA ERP</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/decora.css') }}?v={{ filemtime(public_path('css/decora.css')) }}">
    </head>
    <body>
        @php
            $account = auth()->user();
            $initials = 'DA';
            if ($account) {
                $initials = collect(preg_split('/\s+/', trim($account->name)))
                    ->filter()
                    ->take(2)
                    ->map(fn (string $word): string => mb_strtoupper(mb_substr($word, 0, 1)))
                    ->implode('');
            }
            $role = $account?->getRoleNames()->first() ?: 'Tamu';
        @endphp
        <input class="nav-toggle" id="nav-toggle" type="checkbox">
        <div class="shell">
            <aside class="sidebar">
                <a class="brand" href="{{ route('decora.dashboard') }}">
                    <span class="logo" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 21s7-4.4 7-11a7 7 0 0 0-14 0c0 6.6 7 11 7 11Z"/>
                            <path d="M12 21c0-7 3-10 7-11"/>
                        </svg>
                    </span>
                    <span>
                        <strong>DECORA ERP</strong>
                        <small>ERP Bisnis Dekorasi</small>
                    </span>
                </a>
                <nav class="side-nav" aria-label="Menu utama">
                    <a class="item @yield('nav-dashboard')" href="{{ route('decora.dashboard') }}">
                        @include('decora.icon', ['name' => 'home']) Dashboard
                    </a>

                    <p>Master Data</p>
                    <a class="item" href="{{ url('/admin/pengaturan-perusahaan') }}">@include('decora.icon', ['name' => 'building']) Profil Perusahaan</a>
                    <a class="item" href="{{ url('/admin/users') }}">@include('decora.icon', ['name' => 'users']) Pengguna & Hak Akses</a>
                    <a class="item" href="{{ url('/admin/customers') }}">@include('decora.icon', ['name' => 'user']) Pelanggan</a>
                    <a class="item" href="{{ url('/admin/vendors') }}">@include('decora.icon', ['name' => 'truck']) Vendor / Supplier</a>

                    <p>Operasional</p>
                    <a class="item" href="{{ route('decora.module', 'proyek') }}">@include('decora.icon', ['name' => 'folder']) Proyek Dekorasi</a>
                    <a class="item @yield('nav-inventory')" href="{{ route('decora.inventory') }}">@include('decora.icon', ['name' => 'box']) Gudang & Inventory</a>
                    <a class="item" href="{{ route('decora.module', 'logistik') }}">@include('decora.icon', ['name' => 'pin']) Logistik</a>
                    <a class="item" href="{{ route('decora.module', 'crew') }}">@include('decora.icon', ['name' => 'crew']) Crew & Produksi</a>

                    <p>Keuangan</p>
                    <a class="item" href="{{ route('decora.module', 'penawaran') }}">@include('decora.icon', ['name' => 'invoice']) Penawaran & Invoice</a>
                    <a class="item" href="{{ route('decora.module', 'laporan') }}">@include('decora.icon', ['name' => 'chart']) Laporan</a>

                    <p>Sistem</p>
                    <a class="item" href="{{ url('/admin/pengaturan-perusahaan') }}">@include('decora.icon', ['name' => 'gear']) Pengaturan</a>
                    <a class="item" href="{{ url('/admin/log-aktivitas') }}">@include('decora.icon', ['name' => 'log']) Activity Log</a>
                </nav>
                <a class="admin-link" href="{{ url('/admin') }}">Masuk ke admin</a>
            </aside>
            <div class="workspace">
                <header class="topbar">
                    <label class="menu-button" for="nav-toggle">
                        <span class="sr">Menu</span>
                        <span class="bars" aria-hidden="true"></span>
                    </label>
                    <form class="search" action="{{ route('decora.inventory') }}" method="get">
                        <label class="sr" for="q">Pencarian</label>
                        <input id="q" name="q" type="search" value="{{ request('q') }}" placeholder="Cari proyek, pelanggan, barang, atau invoice..." maxlength="100">
                    </form>
                    <details class="notify">
                        <summary aria-label="Notifikasi">
                            @include('decora.icon', ['name' => 'bell'])
                        </summary>
                        <div>
                            <strong>Notifikasi</strong>
                            <p>Belum ada notifikasi. Pemberitahuan muncul di sini setelah modulnya tersedia.</p>
                        </div>
                    </details>
                    <a class="profile" href="{{ url('/admin') }}">
                        <span class="avatar">{{ $initials }}</span>
                        <span class="profile-text">
                            <strong>{{ $account->name ?? 'Masuk ke admin' }}</strong>
                            <small>{{ $account ? $role : 'Panel Filament' }}</small>
                        </span>
                    </a>
                </header>
                <main>
                    @yield('content')
                </main>
            </div>
        </div>
        <label class="backdrop" for="nav-toggle" aria-hidden="true"></label>
    </body>
</html>
