<?php
/**
 * The Projects Page Template.
 *
 * @package Charlie Jackson
 */
?>

<?php get_header(); ?>
	
	<section id="projects-loop">
	
		<?php
			/**
			 * Get all the project pages and order them by the menu order
			 */
			$args = array(
				'post_type' => 'page',
				'posts_per_page' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC',
				'post_parent' => get_page_by_title( 'Projects' )->ID,
			);
			
			$posts = get_posts( $args );
		?>
		
		<?php foreach ( $posts as $post ) : setup_postdata( $post ); ?>
			
			<?php get_template_part( 'post-formats/content-project' ); ?>
	
		<?php endforeach; ?>
		
	</section>			

<?php get_footer(); ?>