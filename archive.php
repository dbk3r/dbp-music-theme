<?php
// Template-Debug-Ausgabe
if (is_user_logged_in()) {
    echo '<div style="background: #f00; color: #fff; padding: 10px; text-align: center; font-weight: bold;">DEBUG: archive.php wird verwendet!</div>';
}

get_header();
?>
<main>
    <h2><?php the_archive_title(); ?></h2>
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post();
            the_title('<h3>', '</h3>');
            the_excerpt();
        endwhile;
        the_posts_navigation();
    else :
        echo '<p>Keine Einträge gefunden.</p>';
    endif;
    ?>
</main>
<?php
get_footer();
