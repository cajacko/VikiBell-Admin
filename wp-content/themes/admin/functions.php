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

// Update the sitemap on vikibell.com
function update_sitemap() {
    // Action url that builds the sitemap
    $sitemap_action_url = 'https://vikibell.com/action/build-sitemap'; 

    // Unique query string to bypass cache (needed due to limit in number of cloudflare rules)
    $query_string = '?v=' . time();
    $sitemap_action_url .= $query_string;

    // Trigger the action and get the response
    $response = file_get_contents($sitemap_action_url);
    $reponse = json_decode($response);

    // If the action was successful then return true
    if(isset($response['reponse']) && $response['reponse']) {
        return true;
    } else {
        return false;
    }
}

// update_sitemap();

function sync_media() {

}

// Due to free cloudflare plan we must purge everything
function clear_page_cache() {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones/" . CLOUDFLARE_ZONE . "/purge_cache");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

    $headers = [ 
        'X-Auth-Email: ' . CLOUDFLARE_EMAIL,
        'X-Auth-Key: ' . CLOUDFLARE_API,
        'Content-Type: application/json'
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $dnsData = array('purge_everything' => true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dnsData));

    $result = json_decode(curl_exec($ch), true);

    if(isset($result['success']) && $result['success']) {
        return true;
    } else {
        return false;
    }
}

// clear_page_cache();
// exit;

