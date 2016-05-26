<?php

// Setup the theme
function admin_setup() {  
    admin_setup_media();
    add_post_type_support("page", "excerpt");
}

add_action( 'after_setup_theme', 'admin_setup' );

// When a post/page is saved
function admin_save_post($post_id) {
    admin_update_blog_site_on_save();
    admin_save_post_tweet_pref($post_id);
}

add_action('save_post', 'admin_save_post');

// When a category is deleted
function admin_delete_category() {
    admin_update_blog_site_on_cat_delete();
}

add_action('delete_category', 'admin_delete_category'); 

// Add admin notices
function admin_notices() {
    admin_update_blog_site_notices();
    admin_test_connections();
    admin_auto_tweet_length_error();
}

add_action('admin_notices', 'admin_notices');

// Add future to publish functions
function admin_future_post($post_id) {
    admin_tweet_future_post($post_id);
    admin_pages_changes();
}

add_action( 'publish_future_post', 'admin_future_post' );

// Add stuff to the submit box
function admin_submit_box() {
    admin_tweet_submit_box();
}

add_action('post_submitbox_misc_actions', 'admin_submit_box');