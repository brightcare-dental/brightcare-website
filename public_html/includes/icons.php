<?php
/**
 * Refined inline SVG icons — consistent 1.6 stroke, rounded, premium line style.
 * Usage: echo icon('tooth');  — currentColor inherits text colour.
 */
function icon($name, $cls = 'ic') {
    // Multicolor brand mark — official Google "G"
    if ($name === 'google') {
        return '<svg class="'.$cls.'" viewBox="0 0 48 48" aria-hidden="true" focusable="false">'
            .'<path fill="#4285F4" d="M44.5 20H24v8.5h11.8C34.7 33.9 30.1 37 24 37c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.1 0 5.9 1.1 8.1 2.9l6.4-6.4C34.6 4.1 29.6 2 24 2 11.8 2 2 11.8 2 24s9.8 22 22 22c11 0 21-8 21-22 0-1.3-.2-2.7-.5-4z"/>'
            .'<path fill="#FF3D00" d="M6.3 14.7l7 5.1C15.1 16.1 19.2 13 24 13c3.1 0 5.9 1.1 8.1 2.9l6.4-6.4C34.6 4.1 29.6 2 24 2 16 2 9.1 6.5 6.3 14.7z"/>'
            .'<path fill="#4CAF50" d="M24 46c5.5 0 10.5-1.9 14.4-5.2l-6.6-5.6C29.7 36.7 27 37.5 24 37.5c-6.1 0-11.2-3.9-13.1-9.3l-6.9 5.3C7.7 41.4 15.2 46 24 46z"/>'
            .'<path fill="#1976D2" d="M44.5 20H24v8.5h11.8c-.9 2.6-2.6 4.8-4.8 6.3l6.6 5.6C41.7 37.6 45 31.5 45 24c0-1.3-.2-2.7-.5-4z"/>'
            .'</svg>';
    }
    // icons that look better filled (ratings etc.)
    $filled = [
        'star' => '<polygon points="12 2.5 14.85 8.4 21.3 9.3 16.6 13.85 17.75 20.3 12 17.25 6.25 20.3 7.4 13.85 2.7 9.3 9.15 8.4" fill="currentColor" stroke="none"/>',
    ];
    $stroke = [
        'tooth'   => '<path d="M12 3c-1.9 0-2.6 1.1-4 1.1C6.3 4.1 5 5.2 5 7.6c0 2.1.7 4.4 1.4 6.7.6 2 .8 3.9 1.9 3.9 1 0 1.1-2.1 1.4-3.6.2-1 .5-1.7 1.3-1.7s1.1.7 1.3 1.7c.3 1.5.4 3.6 1.4 3.6 1.1 0 1.3-1.9 1.9-3.9.7-2.3 1.4-4.6 1.4-6.7 0-2.4-1.3-3.5-3-3.5-1.4 0-2.1-1.1-4-1.1z"/>',
        'root'    => '<path d="M12 3.2c-1.6 0-2.3 1-3.6 1C7 4.2 6 5.1 6 7.1c0 1.8.5 3.5 1 5"/><path d="M12 3.2c1.6 0 2.3 1 3.6 1C17 4.2 18 5.1 18 7.1c0 1.8-.5 3.5-1 5"/><path d="M10 13c.4 2.4.6 5 1 5 .8 0 .9-2.6 1-4M14 13c-.4 2.4-.6 5-1 5"/>',
        'implant' => '<path d="M12 2.6c-1.8 0-2.4 1-3.7 1C6.7 3.6 5.5 4.7 5.5 7c0 1.6.4 3.2.9 4.7"/><path d="M12 2.6c1.8 0 2.4 1 3.7 1C17.3 3.6 18.5 4.7 18.5 7c0 1.6-.4 3.2-.9 4.7"/><path d="M12 11v3M10.5 14h3M10.8 17h2.4M11 20h2"/>',
        'braces'  => '<path d="M3 9.5h18M3 14.5h18"/><path d="M7.5 7v10M12 7v10M16.5 7v10"/>',
        'sparkle' => '<path d="M12 3l1.6 4.4L18 9l-4.4 1.6L12 15l-1.6-4.4L6 9l4.4-1.6z"/><path d="M18.5 14l.7 1.9 1.9.7-1.9.7-.7 1.9-.7-1.9-1.9-.7 1.9-.7z"/>',
        'child'   => '<circle cx="12" cy="12" r="9"/><path d="M8.5 14.5s1.3 1.8 3.5 1.8 3.5-1.8 3.5-1.8"/><path d="M9 9.5h.01M15 9.5h.01"/>',
        'shield'  => '<path d="M12 22s7.5-3.5 7.5-9.5V5.5L12 2.5 4.5 5.5v7C4.5 18.5 12 22 12 22z"/><path d="M9 12l2 2 4-4"/>',
        'tech'    => '<rect x="4" y="4" width="16" height="16" rx="3"/><rect x="9" y="9" width="6" height="6" rx="1"/><path d="M9 2v2M15 2v2M9 20v2M15 20v2M2 9h2M2 15h2M20 9h2M20 15h2"/>',
        'heart'   => '<path d="M20.4 5.6a5 5 0 0 0-7.1 0L12 6.9l-1.3-1.3a5 5 0 1 0-7.1 7.1L12 21l8.4-8.3a5 5 0 0 0 0-7.1z"/>',
        'wallet'  => '<path d="M3 7a2 2 0 0 1 2-2h12v3"/><path d="M3 7v11a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2H5"/><path d="M17 13.5h.01"/>',
        'phone'   => '<path d="M22 16.9v2.5a2 2 0 0 1-2.2 2 19.6 19.6 0 0 1-8.5-3 19.3 19.3 0 0 1-6-6 19.6 19.6 0 0 1-3-8.6A2 2 0 0 1 4.3 2h2.5a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.6a16 16 0 0 0 6 6l1.1-1.1a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"/>',
        'pin'     => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="2.6"/>',
        'clock'   => '<circle cx="12" cy="12" r="9.2"/><path d="M12 7v5.2l3.3 2"/>',
        'news'    => '<path d="M17 4H5a1 1 0 0 0-1 1v13a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V7"/><path d="M17 4v14a2 2 0 0 0 2 2"/><path d="M7.5 8.5h6M7.5 12h6M7.5 15.5h3.5"/>',
        'check'   => '<path d="M20 6.5 9.2 17.3 4 12.1"/>',
        'arrow'   => '<path d="M5 12h13"/><path d="M12.5 5.5 19 12l-6.5 6.5"/>',
        'wa'      => '<path d="M21 11.6a8.5 8.5 0 0 1-12.6 7.4L3.5 20.5l1.6-4.8A8.5 8.5 0 1 1 21 11.6z"/><path d="M8.8 8.6c.2-.4.4-.4.6-.4h.5c.2 0 .4.1.5.5l.6 1.4c.1.2 0 .4-.1.5l-.4.5c-.1.1-.2.3-.1.5a5 5 0 0 0 2.4 2.4c.2.1.4 0 .5-.1l.5-.5c.1-.1.3-.2.5-.1l1.4.7c.2.1.3.2.3.4 0 .5-.2 1-.6 1.2-.4.3-1.4.6-2.6.1a8 8 0 0 1-4-4c-.5-1.2-.2-2.2.1-2.6z"/>',
        'fb'      => '<path d="M17 2h-3a4.5 4.5 0 0 0-4.5 4.5V10H6.5v4h3v8h4v-8h3l.5-4h-3.5V6.5A.5.5 0 0 1 14 6h3z"/>',
        'ig'      => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="3.8"/><circle cx="17.2" cy="6.8" r="1" fill="currentColor" stroke="none"/>',
        'yt'      => '<rect x="2.5" y="5.5" width="19" height="13" rx="4"/><polygon points="10.2 9 15.5 12 10.2 15" fill="currentColor" stroke="none"/>',
        'menu'    => '<path d="M3.5 7h17M3.5 12h17M3.5 17h17"/>',
        'close'   => '<path d="M18 6 6 18M6 6l12 12"/>',
        'quote'   => '<path d="M7 7h4v6c0 2-1.5 3.5-3.5 4M14 7h4v6c0 2-1.5 3.5-3.5 4"/>',
    ];
    if (isset($filled[$name])) {
        return '<svg class="'.$cls.'" viewBox="0 0 24 24" aria-hidden="true" focusable="false">'.$filled[$name].'</svg>';
    }
    $d = $stroke[$name] ?? '';
    return '<svg class="'.$cls.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'.$d.'</svg>';
}
