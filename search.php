<?php
get_header();
$orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date';
?>
<main>
    <h1>Suchergebnisse</h1>
    <form method="get" style="display: flex; gap: 15px; flex-wrap: wrap; margin-bottom: 2em;">
        <input type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Suchbegriff..." style="padding:8px;">
        <div class="dbp-filter-item">
            <label for="orderby">Sortierung:</label>
            <select id="orderby" name="orderby" onchange="this.form.submit()">
                <option value="date" <?php echo ($orderby == 'date') ? 'selected' : ''; ?>>Neueste zuerst</option>
                <option value="title" <?php echo ($orderby == 'title') ? 'selected' : ''; ?>>Titel A-Z</option>
                <option value="price" <?php echo ($orderby == 'price') ? 'selected' : ''; ?>>Preis</option>
            </select>
        </div>
    </form>
    <?php
    $args = array(
        's' => get_search_query(),
        'orderby' => $orderby,
        'order' => 'DESC',
    );
    $search_query = new WP_Query($args);
    if ($search_query->have_posts()) :
        while ($search_query->have_posts()) : $search_query->the_post();
            the_title('<h2>', '</h2>');
            the_excerpt();
        endwhile;
        the_posts_navigation();
    else :
        echo '<p>Keine Treffer gefunden.</p>';
    endif;
    wp_reset_postdata();
    ?>
</main>
<?php
get_footer();
