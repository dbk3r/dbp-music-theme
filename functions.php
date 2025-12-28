<?php
// Stylesheet einbinden
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('dbp-music-theme-style', get_stylesheet_uri());
});
// Basis-Theme Funktionen
// Hier können später eigene Funktionen ergänzt werden.

// Menü registrieren
add_action('after_setup_theme', function() {
    register_nav_menus([
        'primary' => 'Hauptmenü',
    ]);
});

// Lizenz-PDF Download-Link in WooCommerce-Bestelldetails anzeigen
add_action('woocommerce_order_item_meta_end', function($item_id, $item, $order) {
    // Prüfe, ob eine Lizenz-PDF-URL als Meta gespeichert ist
    $pdf_url = wc_get_order_item_meta($item_id, '_dbp_license_pdf_url', true);
    if ($pdf_url) {
        echo '<p><a href="' . esc_url($pdf_url) . '" target="_blank" class="button">Lizenz-PDF herunterladen</a></p>';
    }
}, 10, 3);

// Optional: Download-Link auch in der Bestellübersicht anzeigen (pro Bestellung, wenn mindestens eine PDF vorhanden ist)
add_action('woocommerce_order_details_after_order_table', function($order) {
    foreach ($order->get_items() as $item) {
        $pdf_url = wc_get_order_item_meta($item->get_id(), '_dbp_license_pdf_url', true);
        if ($pdf_url) {
            echo '<p><a href="' . esc_url($pdf_url) . '" target="_blank" class="button">Lizenz-PDF herunterladen</a></p>';
            break; // Nur einmal pro Bestellung anzeigen
        }
    }
});

// Customizer Farboptionen
add_action('customize_register', function($wp_customize) {
    if (class_exists('WP_Customize_Color_Control')) {
        $farben = [
            'body_bg' => ['#181818', 'body', 'Hintergrundfarbe für <body>'],
            'body_text' => ['#ffe066', 'body', 'Textfarbe für <body>'],
            'link' => ['#ffb347', 'a', 'Farbe für <a>'],
            'link_visited' => ['#ffb347', 'a:visited', 'Farbe für <a:visited>'],
            'link_hover' => ['#ffe066', 'a:hover', 'Farbe für <a:hover>'],
            'input_bg' => ['#222', 'input, textarea, select, button', 'Hintergrund für Formularelemente'],
            'input_text' => ['#ffe066', 'input, textarea, select, button', 'Textfarbe für Formularelemente'],
            'input_border' => ['#7a1a1a', 'input, textarea, select, button', 'Rahmenfarbe für Formularelemente'],
            'table_bg' => ['#222', 'table', 'Tabellenhintergrund'],
            'table_text' => ['#ffe066', 'table', 'Tabellentextfarbe'],
            'table_border' => ['#7a1a1a', 'table, th, td', 'Tabellenrahmen'],
            'hr' => ['#7a1a1a', 'hr', 'Farbe für <hr>'],
        ];
        foreach ($farben as $key => [$default, $css, $desc]) {
            $wp_customize->add_setting($key, [
                'default' => $default,
                'sanitize_callback' => 'sanitize_hex_color',
                'transport' => 'postMessage',
            ]);
            $wp_customize->add_control(new WP_Customize_Color_Control(
                $wp_customize,
                $key,
                [
                    'label' => $css,
                    'description' => $desc,
                    'section' => 'colors',
                    'settings' => $key,
                ]
            ));
        }
    }
});

// Customizer Live-Vorschau Script einbinden
add_action('customize_preview_init', function() {
    wp_enqueue_script(
        'dbp-theme-customizer',
        get_stylesheet_directory_uri() . '/customizer-live.js',
        ['jquery','customize-preview'],
        null,
        true
    );
});

add_action('wp_head', function() {
    $farben = [
        'body_bg' => '#181818',
        'body_text' => '#ffe066',
        'link' => '#ffb347',
        'link_visited' => '#ffb347',
        'link_hover' => '#ffe066',
        'input_bg' => '#222',
        'input_text' => '#ffe066',
        'input_border' => '#7a1a1a',
        'table_bg' => '#222',
        'table_text' => '#ffe066',
        'table_border' => '#7a1a1a',
        'hr' => '#7a1a1a',
    ];
    echo '<style id="dbp-theme-custom-colors">:root {';
    foreach ($farben as $key => $default) {
        $val = get_theme_mod($key);
        if (!$val) $val = $default;
        echo "--$key: $val;\n";
    }
    echo '}</style>';
});
