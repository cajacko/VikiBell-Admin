<section id="sidebar" class="absolute-sidebar">
	<div id="sidebar-container">
		<div id="profile-pic" class="widget sidebar-tablet-hide">
			<div id="profile-pic-wrapper" class="embed-responsive">
				<img id="profile-img" class="embed-responsive-item" height="400" width="400" src="http://placehold.it/400x400">
			</div>
		</div>

		<ul id="sidebar-social-icons">
			<li class="sidebar-social-icon"><a href=""><i class="fa fa-twitter"></i></a></li>
			<li class="sidebar-social-icon"><a href=""><i class="fa fa-instagram"></i></a></li>
			<li class="sidebar-social-icon"><a href=""><i class="fa fa-pinterest"></i></a></li>
		</ul>
		
		<form id="search" method="GET" class="wrap sidebar-tablet-hide">
			<div class="form-group">
				<input type="text" name="s" class="form-control" id="search-input" placeholder="Search">
			</div>
		</form>
		
		<?php
	
			vikibell_display_tweets();
		?>
		
	</div>
</section>