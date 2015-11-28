<footer class="clearfix">
	<?php
	$url = get_post_meta( get_the_ID(), 'bitly', true );

	if( '' == $url ) {
		$url = get_permalink();
	}
	?>

	<span class="post-share-on-text">Share this post on:</span>
	<a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( $url ); ?>&text=<?php echo urlencode( the_title() ); ?>&via=Vikiibell" class="post-share-button post-share-button-twitter" target="_blank"><i class="fa fa-twitter"></i><i class="fa fa-share-alt"></i></a>
	<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( $url ); ?>" class="post-share-button post-share-button-facebook" target="_blank"><i class="fa fa-facebook"></i><i class="fa fa-share-alt"></i></a>
	<a href="mailto:?subject=<?php echo rawurlencode( the_title() ); ?>&body=<?php echo rawurlencode( 'Check out Viki\'s post at: ' . $url ); ?>" class="post-share-button post-share-button-email"><i class="fa fa-envelope"></i><i class="fa fa-share-alt"></i></a>
</footer>