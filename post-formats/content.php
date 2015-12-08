<article <?php post_class( 'new-article' ); ?>>
	<div class="article-container">
		
		<?php 
			$cross_site_sync_url = vikibell_cross_site_sync_url(); 
			$cross_site_sync_featured_image = vikibell_cross_site_sync_featured_image();
		?>

		<?php if( ( vikibell_is_pdf() && vikibell_is_pdf_front() ) || !vikibell_is_pdf() ): ?>
		
			<a class="anchor" id="post-<?php the_ID(); ?>"></a>
			
			<header class="clearfix">
				
				<?php if( vikibell_is_pdf() ): ?>

				<?php else: ?>

					<div class="date">
						<span class="post-date"><?php echo get_the_date( 'd.m' ); ?></span>
					</div>

				<?php endif; ?>
				
				<?php if( has_post_thumbnail() && !vikibell_is_pdf() ): ?>
				
					<?php the_post_thumbnail( 'inline-image', array( 'class' => 'post-featured-image' ) ); ?>
					
				<?php elseif( $cross_site_sync_featured_image ): ?>
				
					<img class="post-featured-image wp-post-image" src="<?php echo $cross_site_sync_featured_image; ?>" width="<?php echo get_post_meta( get_the_ID(), 'cross_site_sync_featured_image_width', true ); ?>" height="<?php echo get_post_meta( get_the_ID(), 'cross_site_sync_featured_image_height', true ); ?>">
					
				<?php endif; ?>

				<div class="header-title wrap">

					<h2><?php vikibell_the_title(); ?></h2>
					
					<?php vikibell_cross_site_sync_message(); ?>
					
				</div>

			</header>

		<?php endif; ?>

		<?php if( !vikibell_is_pdf() || !vikibell_is_pdf_front() ): ?>
		
			<section class="post-body wrap">

				<?php if( !is_single() && has_excerpt() ): ?>

					<?php echo vikibell_get_the_content_with_formatting( true ); ?>

				<?php else: ?>

					<?php echo vikibell_get_the_content_with_formatting(); ?>

				<?php endif; ?>

			</section>
		
			<?php get_template_part( 'sections/post-footer' ); ?>

		<?php endif; ?>
	</div>
</article>