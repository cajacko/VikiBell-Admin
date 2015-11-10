<aside>
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
		WHERE wp_posts.ID != ' . get_the_ID() . ' AND (' . $where . ')  
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

			<?php echo $post->post_title; ?> - <?php echo $post->ID; ?>

		</article>

	<?php endforeach; ?>
</aside>