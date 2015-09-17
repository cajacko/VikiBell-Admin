<?php
/**
 * The main template file.
 *
 * @package Charlie Jackson
 */
?>

<?php get_header(); ?>
	
	<section id="post-loop">
		<?php charliejackson_the_query_title(); ?>
	
		<?php get_template_part( 'sections/post-loop' ); ?>
		
		<?php 
			/**
			 * The pagination is placed outside of the post loop 
			 * template so that it does not get loaded during an 
			 * inifinite scroll request
			 */
			if( have_posts() ) { 
				charliejackson_pagination(); 
			}
		?>
		
	</section>
	
	<?php get_template_part( 'sections/sidebar' ); ?>

<?php get_footer(); ?>