@php
    $paths = [
        'home' => '<path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1Z"/>',
        'building' => '<path d="M4 20V6a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v14"/><path d="M14 10h5a1 1 0 0 1 1 1v9"/><path d="M8 9h2M8 13h2M8 17h2M3 20h18"/>',
        'users' => '<path d="M16 20v-1.2A3.8 3.8 0 0 0 12.2 15H7.8A3.8 3.8 0 0 0 4 18.8V20"/><circle cx="10" cy="8" r="3"/><path d="M20 20v-1.1A3.4 3.4 0 0 0 17.2 16"/><circle cx="17" cy="8.5" r="2.4"/>',
        'user' => '<path d="M19 20v-1.4A4.1 4.1 0 0 0 14.9 14.5H9.1A4.1 4.1 0 0 0 5 18.6V20"/><circle cx="12" cy="8" r="3.2"/>',
        'truck' => '<path d="M3 7h11v10H3z"/><path d="M14 11h4l3 3v3h-7"/><circle cx="7" cy="18.5" r="1.5"/><circle cx="17" cy="18.5" r="1.5"/>',
        'folder' => '<path d="M3 7.5A1.5 1.5 0 0 1 4.5 6H9l2 2h8.5A1.5 1.5 0 0 1 21 9.5v8A1.5 1.5 0 0 1 19.5 19h-15A1.5 1.5 0 0 1 3 17.5Z"/>',
        'box' => '<path d="M3 8 12 4l9 4-9 4Z"/><path d="M3 8v8l9 4 9-4V8"/><path d="M12 12v8"/>',
        'pin' => '<path d="M12 21s6-5.1 6-10a6 6 0 1 0-12 0c0 4.9 6 10 6 10Z"/><circle cx="12" cy="11" r="2"/>',
        'crew' => '<path d="M8 20v-1.2A3.3 3.3 0 0 1 11.3 15.5h1.4A3.3 3.3 0 0 1 16 18.8V20"/><circle cx="12" cy="8" r="2.6"/><path d="M4 20v-.8A2.7 2.7 0 0 1 6.7 16.5"/><circle cx="6.2" cy="10" r="2"/><path d="M20 20v-.8A2.7 2.7 0 0 0 17.3 16.5"/><circle cx="17.8" cy="10" r="2"/>',
        'invoice' => '<path d="M7 3.5h8l4 4V20a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4.5a1 1 0 0 1 1-1Z"/><path d="M15 3.5V8h4"/><path d="M9 12h6M9 16h6"/>',
        'chart' => '<path d="M4 19h16"/><path d="M7 16v-4M12 16V8M17 16v-6"/>',
        'gear' => '<circle cx="12" cy="12" r="3"/><path d="M12 3.5v2.2M12 18.3V20.5M4.8 6.8l1.6 1.6M17.6 15.6l1.6 1.6M3.5 12h2.2M18.3 12h2.2M4.8 17.2l1.6-1.6M17.6 8.4l1.6-1.6"/>',
        'log' => '<path d="M8 6h11M8 12h11M8 18h11"/><circle cx="4.5" cy="6" r="1"/><circle cx="4.5" cy="12" r="1"/><circle cx="4.5" cy="18" r="1"/>',
        'bell' => '<path d="M6 16V11a6 6 0 1 1 12 0v5l1.5 2H4.5Z"/><path d="M10 19a2 2 0 0 0 4 0"/>',
    ];
@endphp
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $paths[$name] !!}</svg>
