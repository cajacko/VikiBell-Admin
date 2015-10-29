<section id="sidebar" class="absolute-sidebar">
	<div id="sidebar-container">
		<div id="profile-pic" class="widget">
			<div id="profile-pic-wrapper" class="embed-responsive">
				<img id="profile-img" class="embed-responsive-item" height="400" width="400" src="http://placehold.it/400x400">
			</div>
		</div>
		
		<form id="search" method="GET" class="wrap">
			<div class="form-group">
				<input type="text" name="s" class="form-control" id="search-input" placeholder="Search">
			</div>
		</form>
		
		<?php
	
			vikibell_display_tweets();
		?>
		
	</div>
</section>