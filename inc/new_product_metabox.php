<?php
add_action('add_meta_boxes', 'myplugin_add_custom_box');
function myplugin_add_custom_box(){
    $screens = array( 'product' );
    add_meta_box( 'myplugin_sectionid', 'New product', 'myplugin_meta_box_callback', $screens, 'normal', 'high' );
}

function myplugin_meta_box_callback( $post, $meta ){
    $screens = $meta['args'];

    wp_nonce_field( plugin_basename(__FILE__), 'myplugin_noncename' );

    $value = get_post_meta( $post->ID, 'new_product', 1 );

    echo '<label for="myplugin_new_field">' . __("New product", 'motors-child' ) . '</label> ';
    if ( 'yes' === $value ) {
        echo '<input type="checkbox" id="myplugin_new_field" name="myplugin_new_field" value="'. $value .'" checked size="25"/>';
    } else {
        echo '<input type="checkbox" id="myplugin_new_field" name="myplugin_new_field" value="'. $value .'" size="25"/>';
    }
}

add_action( 'save_post_product', 'myplugin_save_postdata' );
function myplugin_save_postdata( $post_id ) {
    if ( ! wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename(__FILE__) ) )
        return;

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
        return;

    if( ! current_user_can( 'edit_post', $post_id ) )
        return;

    if( isset( $_POST[ 'myplugin_new_field' ] ) ) {
        update_post_meta( $post_id, 'new_product', 'yes');
    }
    else {
        update_post_meta( $post_id, 'new_product', '' );
    }
}