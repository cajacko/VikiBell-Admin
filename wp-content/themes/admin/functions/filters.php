<?php

// Filter the post before it is saved
function admin_filter_post($data, $args) {
    $data = admin_update_post_guid($data, $args);
    return $data;
}

add_filter( 'wp_insert_post_data', 'admin_filter_post', '99', 2 );