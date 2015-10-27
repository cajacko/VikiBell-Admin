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
	    dropDownMenus();
		
		/**
		 * Hide everything that's only needed if JavaScript is disabled
		 */
	    $( '.hide-without-javascript' ).removeClass( 'hide-without-javascript' );
	    
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
			 	$( '#site-navigation' ).addClass( 'fixed' ); 
			 	$( "main" ).css( "padding-top", $( '#site-navigation' ).height() );  
			} else {
				$( '#site-navigation' ).removeClass( 'fixed' );
				$( "main" ).css( "padding-top", "0px" );
			}
		}
		
		/**
		 * 
		 */
	    function vikibellHoverNav() {
		    $( '.image-to-text' ).mouseenter( function() {
			    if( $( this ).find( 'span' ).hasClass( 'hidden' ) ) {
				    $( this ).find( 'span' ).removeClass( 'hidden' );
				    $( this ).find( 'i' ).addClass( 'hidden' );
				} else {
					$( this ).find( 'span' ).addClass( 'hidden' );
				    $( this ).find( 'i' ).removeClass( 'hidden' );
				}
			} );

			$( '.image-to-text' ).mouseleave( function() {
			    if( $( this ).find( 'span' ).hasClass( 'hidden' ) ) {
				    $( this ).find( 'span' ).removeClass( 'hidden' );
				    $( this ).find( 'i' ).addClass( 'hidden' );
				} else {
					$( this ).find( 'span' ).addClass( 'hidden' );
				    $( this ).find( 'i' ).removeClass( 'hidden' );
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
		    if( $( '#mobile-nav-icon' ).css( "display" ) == 'none' ) {
		   		mobileView = false;
		   	} else {
			   	mobileView = true;
			}
			
			/**
			 * Toggle the mobile menu depending on whether the mobile view is being displayed.
			 */
			if( mobileView ) {
				$( '#mobile-nav-dropdown' ).hide();	
			} else {
				$( '#mobile-nav-dropdown' ).show();
			}
		}
		
		/**
		 * Initiate the dropdown menus for the main navigation
		 */
		function dropDownMenus() {
			/**
			 * Toggle the dropdown menus when hovering 
			 * over the menu items whilst not in a mobile 
			 * view.
			 */
			$( '.site-navigation-item' ).hover( function() {
				/**
				 * On hover hide all the dropdowns but 
				 * show the one for the current item
				 */
				if( !mobileView ) {
					$( '.top-level-nav-link' ).addClass( 'dimmed-nav-item' ).removeClass( 'active-sub-nav' ); // Dim all top level nav items
					$( '.fa-caret-up' ).removeClass( 'fa-caret-up' ).addClass( 'fa-caret-down' ); // Point all the nav arrows down
					$(this).find( '.sub-nav' ).slideDown(); // Show the current dropdown menu
					$(this).find( '.fa-caret-down' ).removeClass( 'fa-caret-down' ).addClass( 'fa-caret-up' ); // Change the current nav arrow to down
					$(this).find( '.top-level-nav-link' ).removeClass( 'dimmed-nav-item' ).addClass( 'active-sub-nav' ); // Make sure the current nav item isn't dimmed
				}
				
			}, function() {
				/**
				 * On hover off the hide all sub navs and 
				 * return everything to their default states
				 */
				if(!mobileView) {
					$( '.sub-nav' ).hide();
					$( '.top-level-nav-link' ).removeClass( 'dimmed-nav-item' ).removeClass( 'active-sub-nav' ); // Turn all the nav items into their default state
					$( '.fa-caret-up' ).removeClass( 'fa-caret-up' ).addClass( 'fa-caret-down' ); // Change all the nav arrows back to down
				}
				
			});
			
			/**
			 * Toggle the top level mobile dropdown menu when 
			 * the menu icon is clicked
			 */
			$( '#mobile-nav-icon' ).click( function() {
				$( '.fa-caret-up' ).removeClass( 'fa-caret-up' ).addClass( 'fa-caret-down' ); // Change all the nav arrows to down
				
				if( $( '#mobile-nav-dropdown' ).is( ':visible' ) ) {
					$( '#mobile-nav-dropdown' ).slideUp(); // Hide the top level nav
					$( '.sub-nav' ).slideUp(); // Hide the sub menu
				} else {
					$( '#mobile-nav-dropdown' ).slideDown(); // Show the top level nav
				}
			});
			
			/**
			 * Toggle the submenus on a mobile layout when the nav items are clicked
			 */
			$( '.top-level-nav-link' ).click( function() {
				$( '.fa-caret-up' ).removeClass( 'fa-caret-up' ).addClass( 'fa-caret-down' ); // Change all the nav arrows to down
				
				if( mobileView && $( this ).siblings( '.sub-nav' ).is( ':visible' ) ) {
					$( '.sub-nav' ).slideUp(); // Hide all the sub navs
				} else if( mobileView ) {
					$( '.sub-nav' ).slideUp(); // Hide all the sub navs
					$( this ).siblings( '.sub-nav' ).slideDown(); // Show the current sub nav
					$( this ).find( '.fa-caret-down' ).removeClass( 'fa-caret-down' ).addClass( 'fa-caret-up' ); // Change the current nav arrow to down
				}
			});
			
			/**
			 * If there is a click anywhere outside the 
			 * visible dropdown nav then hide the navigation
			 */
			$( document ).on( 'click', function( event ) {
				if( !$( event.target ).closest( '#site-navigation-items' ).length && mobileView ) {
					$( '#mobile-nav-dropdown' ).slideUp(); // Hide the main nav
					$( '.sub-nav' ).slideUp(); // Hide the sub nav
					$( '.fa-caret-up' ).removeClass( 'fa-caret-up' ).addClass( 'fa-caret-down' ); // Point all the nav arrows down
				}
			});
		}

}) ( jQuery );