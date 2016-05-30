<?php

// Update the sitemap on vikibell.com
function admin_update_sitemap() {
    // Action url that builds the sitemap
    $sitemap_action_url = 'https://vikibell.com/action/build-sitemap'; 

    // Unique query string to bypass cache (needed due to limit in number of cloudflare rules)
    $query_string = '?v=' . time();
    $sitemap_action_url .= $query_string;

    // Trigger the action and get the response
    $response = file_get_contents($sitemap_action_url);
    // $reponse = json_encode($response);

    $response = json_decode($response, true);

    // If the action was successful then return true and set admin message
    if(isset($response['response']) && $response['response']) {
        $_SESSION['admin_sitemap_updated'] = '<div class="notice notice-success is-dismissible"><p>Sitemap updated.</p></div>';
        return true;
    } else {
        $_SESSION['admin_sitemap_not_updated'] = '<div class="notice notice-error is-dismissible"><p>We could not update your sitemap. Tell Charlie!!!</p></div>';
        return false;
    }
}

// Due to free cloudflare plan we must purge everything
function admin_clear_page_cache() {
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

    // If the cache has been reset then return true and set admin message
    if(isset($result['success']) && $result['success']) {
        $_SESSION['admin_cache_updated'] = '<div class="notice notice-success is-dismissible"><p>Cache reset.</p></div>';
        return true;
    } else {
        $_SESSION['admin_cache_not_cleared'] = '<div class="notice notice-error is-dismissible"><p>We could not clear the page cache. Tell Charlie!!!</p></div>';
        return false;
    }
}

// Update sitemap and clear cache
function admin_pages_changes() {
    admin_update_sitemap();
    admin_clear_page_cache();
    return true;
}

// Update blog site when published pages have changed
function admin_update_blog_site_on_save() {
    $original_post_status = $_POST['original_post_status'];
    $new_post_status = $_POST['post_status'];

    // If the post is published then update the sitemap and clear the cache
    if('publish' == $new_post_status) {
        return admin_pages_changes();
    }

    // If the revious state was publish then update the sitemap and clear cache
    if('publish' == $original_post_status) {
        return admin_pages_changes();
    }
}

// Update blog site when a used category is deleted
function admin_update_blog_site_on_cat_delete() {
    return admin_pages_changes();
} 

// Set admin messages
function admin_update_blog_site_notices(){
    if(!empty($_SESSION['admin_cache_not_cleared'])) {
        print $_SESSION['admin_cache_not_cleared'];
    }

    unset($_SESSION['admin_cache_not_cleared']);

    if(!empty($_SESSION['admin_sitemap_not_updated'])) {
        print $_SESSION['admin_sitemap_not_updated'];
    }
   
    unset($_SESSION['admin_sitemap_not_updated']);

    if(!empty($_SESSION['admin_sitemap_updated'])) {
        print $_SESSION['admin_sitemap_updated'];
    }
   
    unset($_SESSION['admin_sitemap_updated']);

    if(!empty($_SESSION['admin_cache_updated'])) {
        print $_SESSION['admin_cache_updated'];
    }
   
    unset($_SESSION['admin_cache_updated']);
}
