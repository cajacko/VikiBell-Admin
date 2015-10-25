( function( $ ) {

    $( document ).ready( documentReadyFunction );
    $( window ).resize( windowResizeFunction );
    $( window ).scroll( windowScrollFunction );

	var loadingPosts = false; // Global variable to check if posts are already being loaded
		
    function documentReadyFunction() {
        onPageLoadOrResize();
        onPageLoad();
    }

    function windowResizeFunction() {
        onPageLoadOrResize();
    }

    function onPageLoad() {
	    setNewArticlesOpacitytoZero();
	    
	    /**
		 * If JavaScript is enabled then remove the page nav as the infinite scroll will be used.
		 */
	    $( "#page-nav" ).remove();
	    
	    showLoadingImage();
    }
	
    function onPageLoadOrResize () {
	    fadeInArticles();
    }
    
    function windowScrollFunction() {		
		checkPositionAndLoadPosts();
		fadeInArticles();
	}
    
    /* -----------------------------
	SUPPORT FUNCTIONS
	----------------------------- */
		/**
		 * If the user has scrolled within 2 window heights of the bottom of the page then attempt to load more posts.
		 */
		function checkPositionAndLoadPosts() {
			var scroll = $( window ).scrollTop();
		    var windowHeight = $( window ).height();
		    var documentHeight = $( document ).height(); 
		    var setHeight = documentHeight - ( windowHeight * 2 );
		    
		    if( scroll >= setHeight ) {
				loadMorePosts();
			}
		}
		
		/**
		 * Load another set of posts and append 
		 * them to the bottom of the post loop.
		 */
		function loadMorePosts() {
			/**
			 * Load more posts unless the #no-more-posts div is 
			 * showing or a current post request is being made 
			 * or if there is no longer a link to get the next 
			 * posts.
			 */
			if( !$( '#no-more-posts' ).length && !loadingPosts && $( '.next-page-link a' ).length ){
				/**
				 * Get the url from the next page link
				 */
				var url = $( '.next-page-link a' ).last().attr( 'href' );
				
				/**
				 * If there is not a url query for loading posts then add it to the url
				 */
				if( url.indexOf( "?action=load_posts" ) == -1 ){
					url = url + '?action=load_posts';	
				}
				
				/**
				 * Indiciate that posts are being loaded so that multiple requests won't be sent.
				 */
				loadingPosts = true;
				
				/**
				 * Make the AJAX request to get more posts and append the data to the post loop
				 */
				$.ajax( {
					url: url,
					} ).done( function( data ) {
						$( ".loading-img" ).fadeOut( 'slow', function(){
							$( this ).remove();
						});
						
						$( ".next-page-link" ).remove();
						$( "#post-loop" ).append( data );
						setNewArticlesOpacitytoZero();
						loadingPosts = false;
						showLoadingImage();
						
						
						masonry.masonry('reloadItems');
						masonry.masonry('layout');
				});	
			}
		}
		
		/**
		 * Set all the new articles opacity to 0. As they will then 
		 * fade in later when the user scrolls. This is better than 
		 * having all the CSS set the opacity to 0 in case JavaScript 
		 * is disabled.
		 */
		function setNewArticlesOpacitytoZero() {
			$( '.new-article' ).css( 'opacity', '0' );
		}
		
		/**
		 * As soon as a post comes into view fade the opacity to 1.
		 */
		function fadeInArticles() {
			var scrollBottom = $( window ).scrollTop() + $( window ).height();
		
			$( 'article' ).each(  function( i ){ 
	            var articleTop = $( this ).offset().top;

	            if( scrollBottom > articleTop ){
	                $( this ).animate( {'opacity':'1'},500 );
	                $( this ).removeClass( 'new-article' );    
	            }          
	        });
	    }
	    
	    /**
		  * Show the loading image. Used in JavaScript so that it won't display if JavaScript is disabled.
		  */
	    function showLoadingImage() {
		 	$( ".loading-img" ).css( 'display', 'block' );    
		}

})( jQuery );