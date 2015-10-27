<nav class="clearfix">
	<div class="wrap">
		<ul id="site-navigation-list">
			<li id="site-navigation-items">
					<a id="mobile-nav-icon"><i class="fa fa-bars"></i></a>
					
					<ul id="mobile-nav-dropdown">
						<li class="site-navigation-item site-navigation-element image-to-text">
							<a class="top-level-nav-link"><i class="fa fa-female"></i><span class="hidden">About</span></a>
						</li>
						
						<li class="site-navigation-item site-navigation-element image-to-text">
							<a class="top-level-nav-link"><i class="fa fa-leaf"></i><span class="hidden">Life</span></a>
						</li>
						
						<li class="site-navigation-item site-navigation-element image-to-text">
							<a class="top-level-nav-link"><i class="fa fa-calendar"></i><span class="hidden">Events</span></a>
						</li>
						
						<li class="site-navigation-item site-navigation-element image-to-text">
							<a class="top-level-nav-link"><i class="fa fa-plane"></i><span class="hidden">Travel</span></a>
						</li>
						
						<li class="site-navigation-item site-navigation-element image-to-text">
							<a class="top-level-nav-link"><i class="fa fa-cutlery"></i><span class="hidden">Food</span></a>
						</li>
						
						<li class="site-navigation-item site-navigation-element image-to-text">
							<a class="top-level-nav-link"><i class="fa fa-picture-o"></i><span class="hidden">Photo of the Day</span></a>
						</li>
					</ul>
			</li>
		</ul>
	</div>
</nav>

<div id="bunting">
	<div id="bunting-wrap">
		<?php 
			
			$count = 1;
			
			while( $count < 100 ): ?>
				
				<div class="bunting colour-<?php 
					
					if( $count % 5 == 0 ) {
						echo 1;
					} elseif( $count % 4 == 0 ) {
						echo 2;
					} elseif( $count % 3 == 0 ) {
						echo 3;
					} elseif( $count % 2 == 0 ) {
						echo 4;
					} else {
						echo 5;
					}
						
				?>">
					<div class="dot dot-1"></div>
					<div class="dot dot-2"></div>
					<div class="dot dot-3"></div>
					<div class="dot dot-4"></div>
					<div class="dot dot-5"></div>
					<div class="dot dot-6"></div>
					<div class="dot dot-7"></div>
					<div class="dot dot-8"></div>
					<div class="dot dot-9"></div>
				</div>
				
				<?php $count++; ?>
				
			<?php endwhile; 
					
		?>
	</div>
</div>