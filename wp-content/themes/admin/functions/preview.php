<?php

function admin_preview_post_link() {
    $id = get_the_ID();
    $time = time();
    $url = LIVE_BLOG_URL . 'drafts/' . $id . '?version=' . $time;
    return $url;
}