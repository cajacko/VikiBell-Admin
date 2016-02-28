<?php
/**
 * The Portfolio Page Template.
 *
 * @package Charlie Jackson
 */
?>

<?php get_header(); ?>
	
	<section id="post-loop">
		<div id="portfolio-nav" class="display-table">
			<div class="display-table-row">
				<a class="display-table-cell<?php vikibell_portfolio_nav_class( 'all' ); ?>" href="<?php echo home_url(); ?>/portfolio">
					All Projects
				</a>
				
				<a id="portfolio-nav-middle" class="display-table-cell<?php vikibell_portfolio_nav_class( 'web' ); ?>" href="<?php echo home_url(); ?>/portfolio/?portfolio=web">
					Web Portfolio
				</a>
				
				<a class="display-table-cell<?php vikibell_portfolio_nav_class( 'design' ); ?>" href="<?php echo home_url(); ?>/portfolio/?portfolio=design">
					Product Design Portfolio
				</a>
			</div>
		</div>
	
		<?php
			/**
			 * Get all projects that have the portfolio category, 
			 * sorted by the menu order and optionally filtered 
			 * by the web or product design category
			 */
			$args = array(
				'post_type' => 'page',
				'posts_per_page' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC',
			);
			
			$tax_query = array(
				array(
					'taxonomy' => 'project-categories',
					'field'    => 'slug',
					'terms'    => 'portfolio',
				),
			);
			
			if( $_GET['portfolio'] == 'web' ) {
				$tax_query[ 'relation' ] = 'AND';
				
				$tax_query[] = array(
					'taxonomy' => 'project-categories',
					'field'    => 'slug',
					'terms'    => 'web-design',
				);
			} elseif( $_GET['portfolio'] == 'design' ) {
				$tax_query[ 'relation' ] = 'AND';
				
				$tax_query[] = array(
					'taxonomy' => 'project-categories',
					'field'    => 'slug',
					'terms'    => 'product-design',
				);
			}
			
			$args[ 'tax_query' ] = $tax_query; // Add the optional tax query to the post query
			
			$posts = get_posts( $args );
		?>
		
		<?php foreach ( $posts as $post ) : setup_postdata( $post ); ?>
			
			<?php get_template_part( 'post-formats/content-portfolio' ); ?> 
	
		<?php endforeach; ?>
		
	</section>

	<?php get_template_part( 'sections/portfolio-sidebar' ); ?>		

<?php get_footer(); ?>