<aside id="suggested-posts">

	<h3 id="suggest-posts-heading">You might also enjoy</h3>

	<?php
	global $wpdb;

	$query = '
		SELECT wp_term_relationships.term_taxonomy_id
		FROM wp_term_relationships
		WHERE wp_term_relationships.object_id = ' . get_the_ID() . '
	';

	$results = $wpdb->get_results( $query, OBJECT );
	$where = '';
	$count = 0;
	foreach( $results as $result ) {
		if( 0 != $count ) {
			$where .= ' OR ';
		}

		$where .= 'wp_term_relationships.term_taxonomy_id = ' . $result->term_taxonomy_id;

		$count++;
	}

	$query = '
		SELECT wp_posts.ID
		FROM wp_posts
		INNER JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id)
		WHERE wp_posts.ID != ' . get_the_ID() . ' AND (' . $where . ') AND wp_posts.post_status = "publish"
		GROUP BY wp_posts.ID
		ORDER BY COUNT(wp_posts.ID) DESC
		LIMIT 0, 3;
	';

	$results = $wpdb->get_results( $query, OBJECT );

	$posts = array();
	foreach( $results as $result ) {
		$posts[] = $result->ID;
	}

	$offset = 3 - count( $posts );
	$exclude = $posts;
	$exclude[] = get_the_ID();

	if( 0 < $offset ) {
		$args = array(
			'posts_per_page' => $offset,
			'orderby' => 'rand',
			'exclude' => $exclude,
		);

		$rand_posts = get_posts( $args );

		foreach( $rand_posts as $rand_post ) {
			$posts[] = $rand_post->ID;
		}
	}
	?>

	<?php foreach( $posts as $post ): ?>

		<?php $post = get_post( $post ); ?>

		<article>

			<a href="<?php echo home_url( '/?p=' . $post->ID ); ?>" class="suggested-post-container">

				<?php if( has_post_thumbnail( $post->ID ) ): ?>

					<div class="suggested-post-image-container">

						<?php echo get_the_post_thumbnail( $post->ID, 'inline-image', array( 'class' => 'image-fill-container' ) ); ?>

					</div>

				<?php else: ?>

					<?php $background_colours = array( 'bg-blue', 'bg-red', 'bg-orange', 'bg-baige', 'bg-green' ); ?>

					<div class="suggested-post-image-container <?php echo $background_colours[ rand( 0, 4 ) ]; ?>"></div>

				<?php endif; ?>

				<h4><?php echo $post->post_title; ?></h4>

			</a>

		</article>

	<?php endforeach; ?>

</aside>