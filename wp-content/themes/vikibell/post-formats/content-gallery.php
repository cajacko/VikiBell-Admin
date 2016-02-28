<article <?php post_class( 'new-article' ); ?>>
	<div class="article-container">
		<a class="anchor" id="post-<?php the_ID(); ?>"></a>
		
		<header class="clearfix">
			
			<?php vikibell_the_gallery(); ?>
			
			<div class="header-title wrap">
				
				<h2><?php vikibell_the_title(); ?></h2>
				
				<?php vikibell_cross_site_sync_message(); ?>
				
			</div>
		</header>
		
		<section class="post-body wrap">
			
			<?php vikibell_the_gallery_content(); ?>
			
		</section>
		
		<?php get_template_part( 'sections/post-footer' ); ?>
	</div>
</article>