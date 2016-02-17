( function( $ ) {

    $( document ).ready( onPageLoadOrResize );
    $( window ).resize( onPageLoadOrResize );
	
    function onPageLoadOrResize () {
	    /**
		 * If the window is bigger than 600px wide then activate 
		 * the masonry plugin. Otherwise destroy the all effects 
		 * it may have had.
		 */
	    if( $( window ).width() > 600 ) {
		    $( '#projects-loop' ).masonry({
				itemSelector: 'article'
			});
		} else {
			$( '#projects-loop' ).masonry( 'destroy' );
		}
    }
    
}) ( jQuery );