<article class="new-article">
	<div class="article-container">
		<a class="anchor" id="post-<?php the_ID(); ?>"></a>
		
		<header class="clearfix">
			
			<?php if(has_post_thumbnail()): ?>
			
				<?php the_post_thumbnail('inline-image', array('class' => 'post-featured-image')); ?>
				
			<?php endif; ?>
			
			<div class="header-title wrap">
				
				<h2><a target="_blank" href="<?php echo get_the_content(); ?>"><?php the_title(); ?></a></h2>
				
				<?php charliejackson_cross_site_sync_message(); ?>
				
			</div>
		</header>
		
		<?php get_template_part( 'sections/post-footer' ); ?>
	</div>
</article>