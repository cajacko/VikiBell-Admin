<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post(); ?>
	
		<?php if( is_category( 'photo-of-the-day' ) ): ?>
		
			<?php get_template_part( 'post-formats/content', 'photo-of-the-day' ); ?>
		
		<?php else: ?>
		
			<?php get_template_part( 'post-formats/content', get_post_format() ); ?>
		
		<?php endif; ?>

		<?php if( is_single() ): ?>

			<?php get_template_part( 'sections/single-footer' ); ?>
		
		<?php endif; ?>

	<?php endwhile; ?>

	<?php if( get_next_posts_link() && !is_single() && !vikibell_is_pdf() ) : ?>
	
		<div class="next-page-link">
			<?php next_posts_link(); ?>
		</div>
		
		<img class="loading-img" src="<?php echo get_template_directory_uri(); ?>/media/loading-posts.gif">
		
	<?php elseif( !is_page() && !is_single() && !vikibell_is_pdf() ) : ?>
	
		<div id="no-more-posts" class="alert alert-warning" role="alert">That's all folks, there are no more posts.</div>
		
	<?php endif; ?>

<?php endif; ?>