<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Portal internal tim DECORA untuk proyek dekorasi, inventaris, dan produksi acara.">
    <title>@yield('title', 'Portal Internal') — DECORA ERP</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:600,700,800|geist:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/decora-home.css') }}?v={{ filemtime(public_path('css/decora-home.css')) }}">
</head>
<body>
    <header class="site-header">
        <div class="header-inner">
            <a class="brand" href="{{ route('decora.home') }}">
                <span class="brand-mark" aria-hidden="true"></span>
                DECORA ERP
            </a>
            <nav class="site-nav" aria-label="Navigasi utama">
                @foreach ([
                    'decora.home' => 'Beranda',
                    'decora.portal.projects' => 'Proyek',
                    'decora.portal.inventory' => 'Inventaris',
                    'decora.portal.production' => 'Produksi',
                    'decora.portal.workflow' => 'Alur Kerja',
                ] as $name => $label)
                    <a href="{{ route($name) }}" @if (request()->routeIs($name)) aria-current="page" @endif>{{ $label }}</a>
                @endforeach
            </nav>
            <a class="portal-label" href="{{ url('/admin/login') }}">Login</a>
        </div>
    </header>

    <main class="site-main">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-brand">
                <a class="brand" href="{{ route('decora.home') }}">
                    <span class="brand-mark" aria-hidden="true"></span>
                    DECORA ERP
                </a>
                <p>Kelola proyek dekorasi, perlengkapan, dan pemasangan dari satu tempat.</p>
            </div>
            <nav class="footer-nav" aria-label="Navigasi kaki">
                <p>Halaman</p>
                @foreach ([
                    'decora.home' => 'Beranda',
                    'decora.portal.projects' => 'Proyek',
                    'decora.portal.inventory' => 'Inventaris',
                    'decora.portal.production' => 'Produksi',
                    'decora.portal.workflow' => 'Alur Kerja',
                ] as $name => $label)
                    <a href="{{ route($name) }}">{{ $label }}</a>
                @endforeach
            </nav>
            <nav class="footer-nav" aria-label="Akun">
                <p>Akun</p>
                <a href="{{ url('/admin/login') }}">Login</a>
            </nav>
        </div>
        <div class="footer-base">
            <div class="footer-inner">
                <span>© {{ now()->year }} DECORA ERP</span>
                <span>Untuk tim internal</span>
            </div>
        </div>
    </footer>
</body>
</html>
