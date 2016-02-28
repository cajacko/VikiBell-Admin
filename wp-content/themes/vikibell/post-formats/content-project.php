<article <?php post_class( 'new-article' ); ?>>
	<div class="article-container">
		<a class="anchor" id="post-<?php the_ID(); ?>"></a>
		
		<header class="clearfix">
			
			<?php $image_info = wp_get_attachment_image_src( get_post_thumbnail_id(), 'inline-image' ); ?>
			
			<?php if( has_post_thumbnail() && $image_info[2] && $image_info[1] ) : ?>
			
				<?php $percentage = ( $image_info[2] / $image_info[1] ) * 100; ?>
				
				<div class="embed-responsive" style="padding-bottom: <?php echo $percentage; ?>%">
					
					<div class="embed-responsive-item"><?php the_post_thumbnail( 'inline-image', array( 'class' => 'post-featured-image' ) ); ?></div>
					
				</div>
				
			<?php elseif (class_exists('MultiPostThumbnails')):
				    MultiPostThumbnails::the_post_thumbnail(
				        get_post_type(),
				        'portfolio-image',
				        $post->ID,
				        'inline-image',
				        '',
				        true
				    );
				
			endif; ?>
			
			<div class="header-title wrap">
				<h2>
					<?php if( get_the_content() != "" ) : ?>
					
						<a href="<?php echo get_permalink( ); ?>"><?php the_title(); ?></a>
						
					<?php else : ?>
					
						<?php the_title(); ?>
						
					<?php endif; ?>
				</h2>
			</div>
		</header>
		
		<section class="post-body wrap">
			
			<?php echo apply_filters( 'the_content', get_the_excerpt() ); ?>
			
		</section>
		
		<?php get_template_part( 'sections/post-footer' ); ?>
		
	</div>	
</article>