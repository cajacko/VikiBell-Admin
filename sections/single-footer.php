<aside>
	<div class="article-container">
		<?php
		
		$args = array(

		);

		/**
		  * Use custom mysql query that:
		  * - is linked to the taxonomy table and returns all the columns from both tables
		  * - gets all posts with any of the categories of the current post
		  * - gets all posts with any of the tags of the current post
		  * - isn't the current post
		  *
		  * This will return loads of duplicate post ID's. Simple display the 3 posts that are most prevalent in the results.
		  */ 

		$suggested_posts = get_posts( $args );
		?>

		<?php foreach( $suggested_posts as $post ): ?>

			<?php echo $post->post_title; ?>

		<?php endforeach; ?>
	</div>
</aside>