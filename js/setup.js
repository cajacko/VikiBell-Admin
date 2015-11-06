( function( $ ) {

    $( document ).ready( documentReadyFunction );
    $( window ).resize( windowResizeFunction );
    $( window ).scroll( onWindowScroll );
	
	var mobileView = false; // Global variable to define if the mobile view is displayed or not

    function documentReadyFunction() {
        onPageLoadOrResize();
        onPageLoad();
    }

    function windowResizeFunction() {
        onPageLoadOrResize();
    }

    function onPageLoad() {
	    /**
		 * Initiate the dropdown menus
		 */
	    navigationScripts();
		
		/**
		 * Hide everything that's only needed if JavaScript is disabled
		 */
	    $( '.hide-without-javascript' ).removeClass( 'hide-without-javascript' );
	    $( '.no-javascript' ).removeClass( 'no-javascript' );
	    
	    animateScroll();
	    
	    vikibellHoverNav();
    }
	
    function onPageLoadOrResize () {
	    setGlobalVars();
	    vikibellFixNav();
    }
    
    function onWindowScroll() {
	    vikibellFixNav();
	}
    
    /* -----------------------------
	SUPPORT FUNCTIONS
	----------------------------- */
		/**
		 * 
		 */
	    function vikibellFixNav() {
		    var bannerBottom = $( '#banner' ).height();
		    var scroll = $( window ).scrollTop();
		    
		    if( scroll >= bannerBottom ) {
			 	$( '#site-navigation' ).addClass( 'fixed' ).removeClass( 'static' ); 
			 	$( "main" ).css( "padding-top", $( '#site-navigation' ).height() );  
			} else {
				$( '#site-navigation' ).removeClass( 'fixed' ).addClass( 'static' );
				$( "main" ).css( "padding-top", "0px" );
			}
		}
		
		/**
		 * 
		 */
	    function vikibellHoverNav() {
		    $( '.image-to-text' ).hover( function() {
			    if( $( this ).find( '.site-navigation-text' ).hasClass( 'site-navigation-hide' ) ) {
				    $( this ).find( '.site-navigation-text' ).removeClass( 'site-navigation-hide' );
				    $( this ).find( '.site-navigation-icon' ).addClass( 'site-navigation-hide' );
				} else {
					$( this ).find( '.site-navigation-text' ).addClass( 'site-navigation-hide' );
				    $( this ).find( '.site-navigation-icon' ).removeClass( 'site-navigation-hide' );
				}
			} );
		}
		
		/**
		 * Animate the scroll to the desired anchor
		 */
	    function animateScroll() {
			var hashTagActive = ""; // Used to check if the function is already in progress
			
		    $( ".animate-scroll" ).click( function ( event ) {
			    /**
				 * If the function is not already in progress then run the 
				 * function. Used to prevent freezing by clicking on a link 
				 * several times.
				 */
		        if( hashTagActive != this.hash ) {
		            event.preventDefault();
		            
		            /**
			         * Calculate the position to scroll to
			         */
		            var dest = 0;
		            
		            if ( $( this.hash ).offset().top > $( document ).height() - $( window ).height() ) {
		                dest = $( document ).height() - $( window ).height();
		            } else {
		                dest = $( this.hash ).offset().top;
		            }
		            
		            /**
			         * Go to the destination
			         */
		            $( 'html,body' ).animate( {
		                scrollTop: dest
		            }, 500, 'swing', function() {
			            hashTagActive = ""; // When the animation is complete set the variable back to it's default value
			        });
			        
		            hashTagActive = this.hash; // Set the variable as active so the function can't be ran at the same time as itself.
		        }
		    });
		}
		
		/**
		 * Set global variables that are used between various other functions.
		 */
		function setGlobalVars() {
			/**
			 * Is the mobile view being displayed
			 */
		    if( $( '#mobile-nav-main' ).css( "display" ) == 'none' ) {
		   		mobileView = false;
		   	} else {
			   	mobileView = true;
			}
		}
		
		/**
		 * Initiate the dropdown menus for the main navigation
		 */
		function navigationScripts() {
			/**
			 * Toggle the top level mobile dropdown menu when 
			 * the menu icon is clicked
			 */
			$( '#mobile-nav-dropdown-icon' ).click( function() {
				if( $( '#mobile-nav-menu-icon' ).is( ':visible' ) ) {
					$( '#mobile-nav-menu-icon' ).fadeOut();
					setTimeout( function() {
						$( '#mobile-nav-close-icon' ).fadeIn();
					}, 200 );
				} else {
					$( '#mobile-nav-close-icon' ).fadeOut();
					setTimeout( function() {
						$( '#mobile-nav-menu-icon' ).fadeIn();
					}, 200 );
				}
				
				if( $( '#main-nav' ).is( ':visible' ) ) {
					$( '#main-nav' ).slideUp(); // Hide the top level nav
				} else {
					$( '#main-nav' ).slideDown(); // Show the top level nav
				}
			});
			
			/**
			 * If there is a click anywhere outside the 
			 * visible dropdown nav then hide the navigation
			 */
			$( document ).on( 'click', function( event ) {
				if( !$( event.target ).closest( '#site-navigation' ).length && mobileView ) {
					$( '#main-nav' ).slideUp(); // Hide the main nav
				}
			});

			/**
			 * Make sure the main navigation is always 
			 * showing on desktop view
			 */
			$( window ).resize( function() {
				/**
				 * Toggle the mobile menu depending on whether the mobile view is being displayed.
				 */
				if( mobileView ) {
					$( '#main-nav' ).hide();	
				} else {
					$( '#main-nav' ).show();
				}
			});
		}

}) ( jQuery );