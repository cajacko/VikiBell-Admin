<nav class="clearfix">
	<div class="wrap">
		<ul id="site-navigation-list">
			<li id="site-navigation-items">
				<ul id="mobile-nav-main">
					<li class="mobile-nav-main-icon">
						<a id="mobile-nav-dropdown-icon">
							<i id="mobile-nav-menu-icon" class="fa fa-bars"></i>
							<i id="mobile-nav-close-icon" class="fa fa-times"></i>
						</a>
					</li>
					<li class="mobile-nav-main-icon"><a href="/"><i class="fa fa-instagram"></i></a></li>
					<li class="mobile-nav-main-icon"><a href="/"><i class="fa fa-twitter"></i></a></li>
					<li id="mobile-nav-main-title"><a href="/">Viki Bell</a></li>
				</ul>
				
				<ul id="main-nav">
					<li id="site-navigation-mid-logo" class="site-navigation-item site-navigation-element">
						<a class="top-level-nav-link no-javascript">Viki Bell</a>
					</li>

					<li class="site-navigation-item site-navigation-element image-to-text">
						<a class="top-level-nav-link no-javascript"><i class="site-navigation-icon fa fa-female"></i><span class="site-navigation-text site-navigation-hide">About</span></a>
					</li>
					
					<li class="site-navigation-item site-navigation-element image-to-text">
						<a class="top-level-nav-link no-javascript"><i class="site-navigation-icon fa fa-leaf"></i><span class="site-navigation-text site-navigation-hide">Life</span></a>
					</li>
					
					<li class="site-navigation-item site-navigation-element image-to-text">
						<a class="top-level-nav-link no-javascript"><i class="site-navigation-icon fa fa-calendar"></i><span class="site-navigation-text site-navigation-hide">Events</span></a>
					</li>
					
					<li class="site-navigation-item site-navigation-element image-to-text">
						<a class="top-level-nav-link no-javascript"><i class="site-navigation-icon fa fa-plane"></i><span class="site-navigation-text site-navigation-hide">Travel</span></a>
					</li>
					
					<li class="site-navigation-item site-navigation-element image-to-text">
						<a class="top-level-nav-link no-javascript"><i class="site-navigation-icon fa fa-cutlery"></i><span class="site-navigation-text site-navigation-hide">Food</span></a>
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