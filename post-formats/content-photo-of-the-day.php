<article>
	<?php 
		$media = get_attached_media( 'image' );
		
		foreach( $media as $image ) {
			$data = wp_get_attachment_metadata( $image->ID );
			$aspect_ratio = ( $data[ 'height' ] / $data[ 'width' ] ) * 100;
			
			echo '<div class="embed-responsive" style="padding-bottom: ' . $aspect_ratio . '%;"><img class="embed-responsive-item" src="' . wp_get_attachment_url( $image->ID ) . '"></div>';
			
			break;	
		}	
	?>
</article>