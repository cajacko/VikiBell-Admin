<section id="sidebar" class="absolute-sidebar">
	<div id="sidebar-container">
		<img id="profile-pic" height="400" width="400" src="http://placehold.it/400x400">
		
		<form id="search" method="GET" class="wrap">
			<div class="form-group">
				<input type="text" name="s" class="form-control" id="search-input" placeholder="Search">
			</div>
		</form>
		
		<div id="tag-cloud" class="wrap">
			<?php wp_tag_cloud( 'smallest=8&largest=22' ); ?>
		</div>
		
		<a class="twitter-timeline" href="https://twitter.com/charliejackson" data-widget-id="461551137443704833" target="_blank"><div id="twitter-placeholder"><i class="fa fa-twitter"></i> @charliejackson</div></a>
		
	</div>
</section>