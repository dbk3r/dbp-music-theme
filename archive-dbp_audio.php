<?php
/**
 * Template für das Archiv des Custom Post Types dbp_audio
 *
 * @package DBP_Music_Hub
 */

get_header();

// Template-Debug-Ausgabe
if (is_user_logged_in()) {
    echo '<div style="background: #ff0; color: #000; padding: 10px; text-align: center; font-weight: bold;">DEBUG: archive-dbp_audio.php wird verwendet!</div>';
}

// Filter aus GET holen
$genre    = isset($_GET['genre']) ? sanitize_text_field($_GET['genre']) : '';
$category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';
$orderby  = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date';

$args = array(
	'post_type'      => 'dbp_audio',
	'post_status'    => 'publish',
	'posts_per_page' => 20,
	'orderby'        => $orderby,
	'order'          => 'DESC',
);

$tax_query = array();
if ($genre) {
	$tax_query[] = array(
		'taxonomy' => 'dbp_audio_genre',
		'field'    => 'slug',
		'terms'    => $genre,
	);
}
if ($category) {
	$tax_query[] = array(
		'taxonomy' => 'dbp_audio_category',
		'field'    => 'slug',
		'terms'    => $category,
	);
}
if (!empty($tax_query)) {
	$args['tax_query'] = $tax_query;
}

$audio_query = new WP_Query($args);
wp_reset_postdata();



?><div id="primary" class="content-area">
	<main id="main" class="site-main">

		<div class="dbp-archive-filters">
			<form method="get" style="display: flex; gap: 15px; flex-wrap: wrap;">
				<div class="dbp-filter-item">
					<label for="orderby">Sortierung:</label>
					<select id="orderby" name="orderby" onchange="this.form.submit()">
						<option value="date" <?php echo ($orderby == 'date') ? 'selected' : ''; ?>>Neueste zuerst</option>
						<option value="title" <?php echo ($orderby == 'title') ? 'selected' : ''; ?>>Titel A-Z</option>
						<option value="price" <?php echo ($orderby == 'price') ? 'selected' : ''; ?>>Preis</option>
					</select>
				</div>
				<?php if ($genre): ?><input type="hidden" name="genre" value="<?php echo esc_attr($genre); ?>" /><?php endif; ?>
				<?php if ($category): ?><input type="hidden" name="category" value="<?php echo esc_attr($category); ?>" /><?php endif; ?>
			</form>
		</div>

		<?php if ( $audio_query->have_posts() ) : ?>
			<div class="dbp-audio-grid">
				<?php while ( $audio_query->have_posts() ) : $audio_query->the_post();
					$audio_id = get_the_ID();
					$artist   = get_post_meta( $audio_id, '_dbp_audio_artist', true );
					$album    = get_post_meta( $audio_id, '_dbp_audio_album', true );
					$duration = get_post_meta( $audio_id, '_dbp_audio_duration', true );
					$price    = get_post_meta( $audio_id, '_dbp_audio_price', true );
				?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'dbp-audio-card' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
						<div class="dbp-card-thumbnail">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail( 'medium_large' ); ?>
							</a>
						</div>
						<?php endif; ?>

						<div class="dbp-card-content">
							<h2 class="dbp-card-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>

							<?php if ( $artist ) : ?>
							<div class="dbp-card-meta">
								<strong><?php esc_html_e( 'Künstler:', 'dbp-music-hub' ); ?></strong> <?php echo esc_html( $artist ); ?>
							</div>
							<?php endif; ?>

							<?php if ( $album ) : ?>
							<div class="dbp-card-meta">
								<strong><?php esc_html_e( 'Album:', 'dbp-music-hub' ); ?></strong> <?php echo esc_html( $album ); ?>
							</div>
							<?php endif; ?>

							<?php if ( $duration ) : ?>
							<div class="dbp-card-meta">
								<strong><?php esc_html_e( 'Dauer:', 'dbp-music-hub' ); ?></strong> <?php echo esc_html( $duration ); ?>
							</div>
							<?php endif; ?>

							<?php
							// Player (Waveform wenn aktiviert)
							if ( class_exists( 'DBP_Audio_Player' ) ) {
								echo DBP_Audio_Player::render_player( $audio_id, false );
							}
							?>

							<?php if ( has_excerpt() ) : ?>
							<div class="dbp-card-excerpt">
								<?php the_excerpt(); ?>
							</div>
							<?php endif; ?>

							<div class="dbp-card-footer">
								<?php if ( $price ) : ?>
								<div class="dbp-card-price">
									<?php echo esc_html( number_format_i18n( $price, 2 ) ); ?> €
								</div>
								<?php endif; ?>

								<a href="<?php the_permalink(); ?>" class="dbp-card-link">
									<?php esc_html_e( 'Details ansehen', 'dbp-music-hub' ); ?>
								</a>
							</div>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
		<?php else : ?>
			<div class="dbp-no-results">
				<p><?php esc_html_e( 'Keine Audio-Dateien gefunden.', 'dbp-music-hub' ); ?></p>
			</div>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>

	</main>
</div>

<?php
get_sidebar();
get_footer();
