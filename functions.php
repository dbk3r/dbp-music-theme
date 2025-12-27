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
