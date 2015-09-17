( function( $ ) {

    $( document ).ready( portfolioNavHighlight );
    $( window ).scroll( portfolioNavHighlight );
    
	/**
	 * Highlight the currently viewed portfolio item in the sidebar
	 */
	function portfolioNavHighlight() {
		var scrollMiddle = $( window ).scrollTop() + ( $( window ).height()/2 ); // Get the middle scroll position of the window

		/**
		 * Check which article is in the middle of the 
		 * screen and then highlight that article in 
		 * the sidebar
		 */
		$( 'article' ).each( function() { 
            var articleTop = $( this ).offset().top; // Get the articles top position
            var articleBottom = articleTop + $( this ).outerHeight( true ); // Get the articles bottom position
            var navId = $( this ).find( '.anchor' ).attr( 'id' ); // Get the id of the article            
            navId = '#nav-' + navId; // Set the var navId to the id of the nav element to target
			
			/**
			 * If the middle scroll position is inbetween 
			 * the current article then highlight it in 
			 * the sidebar
			 */
            if( scrollMiddle > articleTop && scrollMiddle < articleBottom ) {
	            $( navId ).addClass( 'active-portfolio-item' );
	        } else {
		     	$( navId ).removeClass( 'active-portfolio-item' );
		    }
        });
    }

}) ( jQuery );