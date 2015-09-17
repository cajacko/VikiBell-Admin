<section id="sidebar" class="absolute-sidebar portfolio-sidebar">
	<div id="sidebar-container">
		<h1>Portfolio</h1>
		
		<ul>
			<li><a class="portfolio-link animate-scroll" href="#top-of-page">Top of page</a></li>
			
			<?php $count = 0; ?>
				
			<?php foreach ( $posts as $post ) : setup_postdata($post); ?>
					
				<li id="nav-post-<?php the_ID(); ?>"<?php charliejackson_portfolio_affix_nav_classes($count); ?>>
					
					<a class="portfolio-link animate-scroll" href="#post-<?php the_ID(); ?>"><?php the_title(); ?></a>
					
				</li>
				
				<?php $count++; ?>
				
			<?php endforeach; ?>
		</ul>
	</div>
</section>	