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
