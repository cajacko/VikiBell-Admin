( function( $ ) {

    $( document ).ready( documentReadyFunction );
    $( window ).resize( windowResizeFunction );
	
    function documentReadyFunction() {
        onPageLoadOrResize();
        onPageLoad();
    }

    function windowResizeFunction() {
        onPageLoadOrResize();
    }

    function onPageLoad() {
    	$( '#banner' ).css( 'background-image', 'initial' ); //Remove the background image if javascript is enabled
    }
	
    function onPageLoadOrResize () {

	    imageFillContainer( $( '#banner-img' ) ); //Make the banner image fill it's parent
    }
	
	/* -----------------------------
	SUPPORT FUNCTIONS
	----------------------------- */	
		/**
		 * Make an element fill it's parent and vertically and horizontally center.
		 */
		function imageFillContainer( image ) {
			var parent = $( image ).parent();
			var parentHeight = $( parent ).height();
			var parentWidth = $( parent ).width();
			var parentAspectRatio = parentHeight / parentWidth;
			
			var imageHeight = $( image ).attr('height');
			var imageWidth = $( image ).attr('width');
			var imageAspectRatio = imageHeight / imageWidth;
			
			/**
			 * Check if the element if portrait or landscape and handle accordingly.
			 */
			if( imageAspectRatio > parentAspectRatio ) {
				imageHeight = imageAspectRatio * ( parentHeight / parentAspectRatio ); //Use the aspect ratios to calculate the new height
				var marginTop = '-' + ( imageHeight/2 ) + 'px';
				
				$( image ).css( {
					'width' : '100%',
					'height' : 'auto',
					'left' : 0,
					'top' : '50%',
					'margin-top' : marginTop,
					'margin-left' : 'auto',
				});
			} else {
				imageWidth =  parentHeight / imageAspectRatio; // Use the aspect ratio to calculate the new width
				var marginLeft = '-' + ( imageWidth/2 ) + 'px';
				
				$( image ).css( {
					'height' : '100%',
					'width' : 'auto',
					'left' : '50%',
					'top' : 0,
					'margin-left' : marginLeft,
					'margin-top' : 'auto',
				});
			}
		}

})( jQuery );