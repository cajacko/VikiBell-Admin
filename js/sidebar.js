( function( $ ) {

    $( document ).ready( documentReadyFunction );
    $( window ).resize( windowResizeFunction );
    $( window ).scroll( windowScrollFunction );
	
	/**
	 * Set global variables which are used by the 
	 * various functions so that value's don't 
	 * have to be constantly recalculated.
	 */
	var lastScrollTop = 0;
	var sidebarTopPosition = 0;
	var globalPadding = 20;
	var windowHeight = 0;
	var windowWidth = 0;
	var wrapMaxWidth = 960;
	var areGlobalVarsSet = false;
	var minHeightForFixedNav = 600;
	var isHeightTooSmallForFixedNav = false;
	var scrollToTopHeight = 90;

    function documentReadyFunction() {
        onPageLoadOrResize();
        onPageLoad();
    }

    function windowResizeFunction() {
        onPageLoadOrResize();
    }

    function onPageLoad() {
	    twitterTimeline();
	    $('#twitter-placeholder').addClass('javascript-enabled');
    }
	
    function onPageLoadOrResize () {
	    setGlobalVars();
  		topPaddingForFixedNavConpensation();
  		positionSidebar();
    }
    
    function windowScrollFunction() {
	    /**
		 * Make sure the global variables are set before positioning 
		 * the sidebar. Otherwise weird things may happen.
		 */
	    if( areGlobalVarsSet ) {
		    positionSidebar();
		}	   
	}
    
    /* -----------------------------
	SUPPORT FUNCTIONS
	----------------------------- */
		/**
		 * Define the global variables
		 */
		function setGlobalVars() {
		    globalPadding = $( "main" ).css( 'padding-bottom' );
		    globalPadding = parseInt( globalPadding );	
    
		    windowHeight = $( window ).height();	
		    windowWidth = $( window ).width();	  
		      
		    wrapMaxWidth = $( ".wrap" ).css( 'max-width' );
		    wrapMaxWidth = parseInt( wrapMaxWidth );
		    
		    minHeightForFixedNav = $( '#less-vars' ).css( 'height' );
		    minHeightForFixedNav = parseInt( minHeightForFixedNav );

		    if( windowHeight < minHeightForFixedNav ) {
			    isHeightTooSmallForFixedNav = true;
			} else {
				isHeightTooSmallForFixedNav = false;
			}
			
			$( '.sub-nav' ).hide();
			
			areGlobalVarsSet = true;
		}
		
		/**
		 * Position the sidebar so that it is either at the 
		 * top of the post loop, fixed to the top or fixed 
		 * to the bottom. Depending on the scroll position 
		 * and direction.
		 */
		function positionSidebar() {
			if( windowWidth >= wrapMaxWidth ) {
			    var scroll = $( window ).scrollTop();
			    var scrollBottom = scroll + windowHeight;
			    
			    var sidebarHeight = $( "#sidebar-container" ).outerHeight();
			    var totalSidebarHeight = sidebarHeight + ( globalPadding * 2 );
			    
			    var sidebarPosition = $( "#sidebar" ).offset();
			    var sidebarTop = sidebarPosition['top'];
			    
			    var fixedToBottomTopPosition = scrollBottom - sidebarTop - sidebarHeight - scrollToTopHeight;
			    var fixedToTopTopPosisiton = scroll - sidebarTop + globalPadding;
			    
			    var bottomGap = scrollBottom - sidebarTop - sidebarHeight - sidebarTopPosition - scrollToTopHeight;
			    var topGap = sidebarTopPosition - ( scroll - sidebarTop ) - globalPadding;
			    
			    if( isHeightTooSmallForFixedNav ) {
				    fixedToTopTopPosisiton = fixedToTopTopPosisiton;
				}
			    
			    /**
				 * Make sure there is never a visible gap between the top 
				 * of the post loop and the top of the sidebar
				 */
			    if( fixedToTopTopPosisiton <= 0 ) {
					$( "#sidebar" ).addClass( 'absolute-sidebar' ).removeClass( 'fixed-bottom-sidebar' ).removeClass( 'fixed-top-sidebar' );
					$( "#sidebar-container" ).css( "top", 'auto' ).css( "bottom", "auto" );
					sidebarTopPosition = 0;
				} 
				/**
				 * Make sure the gap between the bottom of the site nav and 
				 * the top of the sidebar can not be bigger than the global 
				 * padding
				 */
				else if( totalSidebarHeight < windowHeight || topGap >= 0 ) {
					if( isHeightTooSmallForFixedNav ) {
						var topOffset = globalPadding;
					} else {
						var topOffset = globalPadding;
					}
						
					$( "#sidebar" ).removeClass( 'absolute-sidebar' ).removeClass( 'fixed-bottom-sidebar' ).addClass( 'fixed-top-sidebar' );
					$( "#sidebar-container" ).css( "top", topOffset + "px" ).css( "bottom", "auto" );
					sidebarTopPosition = fixedToTopTopPosisiton;
				} 
				/**
				 * As long as the sidebar is tall enough, make sure the gap 
				 * between the bottom of the sidebar and the bottom of the 
				 * window is never bigger than the global padding
				 */
				else if( ( bottomGap >= 0 && scroll > lastScrollTop ) || ( $( "#sidebar" ).hasClass( "fixed-bottom-sidebar" ) && scroll > lastScrollTop ) ) {
					$( "#sidebar" ).removeClass( 'absolute-sidebar' ).addClass( 'fixed-bottom-sidebar' ).removeClass( 'fixed-top-sidebar' );
					
					$( "#sidebar-container" ).css( "top", 'auto' ).css( "bottom", scrollToTopHeight + "px" );
					sidebarTopPosition = fixedToBottomTopPosition;
				} 
				/**
				 * Otherwise, fix the position of the sidebar and allow the user to scroll up and down it freely.
				 */
				else {
					$( "#sidebar-container" ).css( "top", sidebarTopPosition + 'px' ).css( "bottom", "auto" );
					$( "#sidebar" ).addClass( 'absolute-sidebar' ).removeClass( 'fixed-bottom-sidebar' ).removeClass( 'fixed-top-sidebar' );
				}
				
				lastScrollTop = scroll;
			}
		}
		
		/**
		 * Pad the top of the main element so that all the spacing 
		 * is consisent. Also set all the anchors to be offset by 
		 * enough so that the anchor links will display with the 
		 * correct amount of spacing.
		 */
		function topPaddingForFixedNavConpensation() {
			var anchorHeight = globalPadding;
	  		$( ".anchor" ).css( "top", -anchorHeight );
		}
		
		/**
		 * Display the Twitter Timeline
		 */
		function twitterTimeline() {	    	
    		!function( d,s,id ){
	    		var js,fjs = d.getElementsByTagName( s )[0], p = /^http:/.test( d.location ) ? 'http' : 'https';
	    		
	    		if( !d.getElementById( id ) ) {
	    			js = d.createElement( s );
	    			js.id = id;
	    			js.src = p + "://platform.twitter.com/widgets.js";
	    			fjs.parentNode.insertBefore( js,fjs );
	    			
	    			console.log("Twitter set up");
	    		}
	    	} ( document,"script","twitter-wjs" );
	    	
	    }	

}) ( jQuery );