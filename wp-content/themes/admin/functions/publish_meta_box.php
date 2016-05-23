<?php

function article_or_box() {
    global $post;
    if (get_post_type($post) == 'post') {
        echo '<div class="misc-pub-section misc-pub-section-last" style="border-top: 1px solid #eee;">';
        wp_nonce_field( plugin_basename(__FILE__), 'article_or_box_nonce' );
        $val = get_post_meta( $post->ID, '_article_or_box', true ) ? get_post_meta( $post->ID, '_article_or_box', true ) : 'article';
        echo '<input type="radio" name="article_or_box" id="article_or_box-article" value="article" '.checked($val,'article',false).' /> <label for="article_or_box-article" class="select-it">Article</label><br />';
        echo '<input type="radio" name="article_or_box" id="article_or_box-box" value="box" '.checked($val,'box',false).'/> <label for="article_or_box-box" class="select-it">Box</label>';
        echo '</div>';
    }
}

function save_article_or_box($post_id) {

    if (!isset($_POST['post_type']) )
        return $post_id;

    if ( !wp_verify_nonce( $_POST['article_or_box_nonce'], plugin_basename(__FILE__) ) )
        return $post_id;

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
        return $post_id;

    if ( 'post' == $_POST['post_type'] && !current_user_can( 'edit_post', $post_id ) )
        return $post_id;
    
    if (!isset($_POST['article_or_box']))
        return $post_id;
    else {
        $mydata = $_POST['article_or_box'];
        update_post_meta( $post_id, '_article_or_box', $_POST['article_or_box'], get_post_meta( $post_id, '_article_or_box', true ) );
    }

}