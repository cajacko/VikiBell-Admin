<?php
/**
 * The single template file.
 */
?>

<?php get_header(); ?>
	
	<section id="post-loop">
		<div id="post-loop-wrap">
			<?php vikibell_the_query_title(); ?>
		
			<?php get_template_part( 'sections/post-loop' ); ?>
			
		</div>
		
	</section>
	
	<?php if( !is_category( 'photo-of-the-day' ) ): ?>
	
		<?php get_template_part( 'sections/sidebar' ); ?>
		
	<?php endif; ?>

<?php get_footer(); ?>