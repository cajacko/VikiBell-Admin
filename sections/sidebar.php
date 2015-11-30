<section id="sidebar" class="absolute-sidebar">
	<div id="sidebar-container">
		<div id="sidebar-main">
			<div id="profile-pic" class="widget sidebar-tablet-hide">
				<div id="profile-pic-wrapper" class="embed-responsive">
					<img id="profile-img" class="embed-responsive-item" height="400" width="400" src="/wp-content/themes/vikibell/media/profile.jpg">
				</div>
			</div>

			<ul id="sidebar-social-icons" class="clearfix">
				<li id="sidebar-twitter" class="sidebar-social-icon"><a target="_blank" href="https://twitter.com/Vikiibell"><i class="fa fa-twitter"></i></a></li>
				<li id="sidebar-instagram" class="sidebar-social-icon"><a target="_blank" href="https://www.instagram.com/vikibell/"><i class="fa fa-instagram"></i></a></li>
				<li id="sidebar-pinterest" class="sidebar-social-icon"><a target="_blank" href="https://www.pinterest.com/vikiibell/"><i class="fa fa-pinterest"></i></a></li>
			</ul>
			
			<form id="search" method="GET" class="wrap sidebar-tablet-hide">
				<div class="form-group">
					<input type="text" name="s" class="form-control" id="search-input" placeholder="Search">
				</div>
			</form>
			
			<?php vikibell_display_tweets(); ?>
		</div>

		<div id="scroll-to-top">
			<a href="#top-of-page">
				<i class="fa fa-chevron-up"></i>
			</a>
		</div>
		
	</div>
</section>