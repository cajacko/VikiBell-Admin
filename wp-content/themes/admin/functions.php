<?php

// Setup theme
function admin_setup() {  
    add_theme_support( 'post-thumbnails' );
    
    /*
     * Add various image sizes so that images 
     * can be progressively loaded at higher 
     * resolutions.
     */
    for($i = 600; $i > 300; $i = $i - 50) {
        add_image_size( 'width-' . $i, $i );
    }
    
    add_post_type_support( "page", "excerpt" );
}

add_action( 'after_setup_theme', 'admin_setup' );

// Set upload size
@ini_set( ‘upload_max_size’ , ‘10G’);
@ini_set( ‘post_max_size’, ‘10G’);  
@ini_set( ‘max_execution_time’, ‘300’);