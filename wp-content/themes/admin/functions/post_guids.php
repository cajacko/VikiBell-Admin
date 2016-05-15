<?php

// Point the post guid to the post on the blog site
function admin_update_post_guid($data, $args) {
    $guid = LIVE_BLOG_URL;

    // Append the url for the guid
    if('post' == $data['post_type']) {
        $guid .= 'posts/';
    } elseif('page' != $data['post_type']) {
        return $data;
    }

    // Set the new guid and return the info
    $guid .= $data['post_name'];
    $data['guid'] = $guid;
    return $data;
}
