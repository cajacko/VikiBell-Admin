( function( $ ) {

    $( document ).ready( documentReadyFunction );
    $( window ).resize( windowResizeFunction );
    $( window ).scroll( positionNav );
	
    function documentReadyFunction() {
        onPageLoadOrResize();
        onPageLoad();
    }

    function windowResizeFunction() {
        onPageLoadOrResize();
    }

    function onPageLoad() {
	   	pulseBlogLink();
	   	$( '#banner' ).css( 'background-image', 'none' ); // If JavaScript is enabled then remove the CSS background image
    }
	
    function onPageLoadOrResize () {
	    positionBannerElements();
	    positionNav();
	    imageFillContainer( $( '#banner-img' ) ); //Make the banner image fill it's parent
    }
	
	/* -----------------------------
	SUPPORT FUNCTIONS
	----------------------------- */
		/**
		 * If the user has scolled to the bottom of the banner then 
		 * fix the navigation to the top of the screen.
		 */  
	    function positionNav() {
		    var scroll = $( window ).scrollTop();
		    var bannerHeight = $( "#banner" ).height();
	
		    if( scroll >= bannerHeight ) {
				$( '#site-navigation' ).removeClass( 'absolute-nav' ).addClass( 'fixed-nav' ); 
			} else {
				$( '#site-navigation' ).addClass( 'absolute-nav' ).removeClass( 'fixed-nav' );
			}
		}
	    
	    /**
		 * If the user has scolled to the bottom of the banner then 
		 * fix the navigation to the top of the screen.
		 */ 
	    function positionBannerElements() {
		    var windowHeight = $( window ).height();
		    var windowWidth = $( window ).width();
		    var asideHeight = $( "#banner aside" ).height();
		    var minBannerHeight = parseInt( $( "#banner" ).css( "min-height" ) );
		    var bannerMobileBreak = $( "#banner" ).data( "banner-mobile-width" );
		    
		    /**
			 * Set the position of the banner aside to be 
			 * vetically centered within the banner div
			 */
		    if( windowHeight < minBannerHeight ) {
			    var asideTop = ( minBannerHeight - asideHeight ) / 2;
			} else {
		    	var asideTop = ( windowHeight - asideHeight ) / 2;
		    }
		    
		    /**
			 * If the window height is bigger than the mobile 
			 * break point then make the banner fill the window 
			 * height and vertically align the aside. Otherwise 
			 * let the default CSS dictate the layout.
			 */
		    if( bannerMobileBreak < windowWidth ) {	    
			    $( "#banner" ).height( windowHeight );
			    $( "#banner aside" ).css( "bottom", asideTop );
			} else {
				$( "#banner" ).height( 'auto' );
			    $( "#banner aside" ).css( "bottom", 'auto' );
			}
		}
		
		/**
		 * Make the go to blog link gentle pulsate 
		 * by varying it's opacity.
		 */ 
		function pulseBlogLink() {
			/**
			 * If the opacity is 0.5 then adjust to to 1, 
			 * otherwise reduce the opacity to 0.5. When 
			 * the animation is complete the function is 
			 * called again so that it loops.
			 */
		    if( $( '#go-to-blog' ).css( 'opacity' ) == 0.5 ) {
			 	$( "#go-to-blog" ).animate( {
					opacity: 1
				}, 2000, function() {
					pulseBlogLink();
				}); 
			} else {
				$( "#go-to-blog" ).animate( {
					opacity: 0.5
				}, 2000, function() {
					pulseBlogLink();
				});
			} 
		}
		
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