<?php
// Basis-Theme Funktionen
// Hier können später eigene Funktionen ergänzt werden.

// Menü registrieren
add_action('after_setup_theme', function() {
    register_nav_menus([
        'primary' => 'Hauptmenü',
    ]);
});
