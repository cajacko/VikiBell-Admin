<footer class="clearfix">
	<span class="post-share-on-text">Share this post on:</span>
	<a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&text=<?php echo urlencode( 'text of some sort' ); ?>&via=Vikiibell" class="post-share-button post-share-button-twitter" target="_blank"><i class="fa fa-twitter"></i><i class="fa fa-share-alt"></i></a>
	<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" class="post-share-button post-share-button-facebook" target="_blank"><i class="fa fa-facebook"></i><i class="fa fa-share-alt"></i></a>
	<a href="mailto:?subject=<?php echo rawurlencode( 'Viki\'s awesome site' ); ?>&body=<?php echo rawurlencode( 'Check out Viki\'s post at: ' . get_permalink() ); ?>" class="post-share-button post-share-button-email"><i class="fa fa-envelope"></i><i class="fa fa-share-alt"></i></a>
</footer>