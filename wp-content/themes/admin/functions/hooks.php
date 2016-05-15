<?php

// Setup the theme
function admin_setup() {  
    admin_setup_media();
    add_post_type_support( "page", "excerpt" );
}

add_action( 'after_setup_theme', 'admin_setup' );

// When a post/page is saved
function admin_save_post($post_id) {
    // admin_update_blog_site_on_save();
}

add_action('save_post', 'admin_save_post');

// When a category is deleted
function admin_delete_category() {
    admin_update_blog_site_on_cat_delete();
}

add_action('delete_category', 'admin_delete_category'); 

// Add admin notices
function admin_notices(){
    admin_update_blog_site_notices();
}

add_action('admin_notices', 'admin_notices');
