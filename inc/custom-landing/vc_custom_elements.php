<?php

if (function_exists('vc_map')) {
	add_action('init', 'register_child_custom_elements');
}

function register_child_custom_elements()
{

	 $order_by_values = array(
        '',
        __( 'Date', 'stm-woocommerce-motors-auto-parts' ) => 'date',
        __( 'ID', 'stm-woocommerce-motors-auto-parts' ) => 'ID',
        __( 'Author', 'stm-woocommerce-motors-auto-parts' ) => 'author',
        __( 'Title', 'stm-woocommerce-motors-auto-parts' ) => 'title',
        __( 'Modified', 'stm-woocommerce-motors-auto-parts' ) => 'modified',
        __( 'Random', 'stm-woocommerce-motors-auto-parts' ) => 'rand',
        __( 'Comment count', 'stm-woocommerce-motors-auto-parts' ) => 'comment_count',
        __( 'Menu order', 'stm-woocommerce-motors-auto-parts' ) => 'menu_order',
        __( 'Menu order & title', 'stm-woocommerce-motors-auto-parts' ) => 'menu_order title',
        __( 'Include', 'stm-woocommerce-motors-auto-parts' ) => 'include',
    );

    $order_way_values = array(
        '',
        __( 'Descending', 'stm-woocommerce-motors-auto-parts' ) => 'DESC',
        __( 'Ascending', 'stm-woocommerce-motors-auto-parts' ) => 'ASC',
    );

    $productsType = array(
        '',
		esc_html__('New', 'stm-woocommerce-motors-auto-parts') => 'best_selling',
        esc_html__('Best Selling Products', 'stm-woocommerce-motors-auto-parts') => 'best_selling',
        esc_html__('Top Rated Products', 'stm-woocommerce-motors-auto-parts') => 'top_rated',
    );
 vc_map( array(
                'html_template' => get_stylesheet_directory() . '/vc_templates/stm_wcmap_products_list.php',
                'name' => __( 'STM WC Custom Products', 'stm-woocommerce-motors-auto-parts' ),
                'base' => 'stm_wcmap_custom_products_list',
                'icon' => 'icon-wpb-woocommerce',
                'category' => __( 'STM Auto Parts', 'stm-woocommerce-motors-auto-parts' ),
                'description' => __( 'Show multiple products by ID or SKU.', 'stm-woocommerce-motors-auto-parts' ),
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => __( 'Columns', 'stm-woocommerce-motors-auto-parts' ),
                        'param_name' => 'columns',
                        'value' => array(
                            4 => 4,
                            3 => 3
                        ),
                        'std' => '4',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Number Posts', 'js_composer' ),
                        'value' => 8,
                        'param_name' => 'number_posts',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __( 'Order by', 'stm-woocommerce-motors-auto-parts' ),
                        'param_name' => 'orderby',
                        'value' => $order_by_values,
                        'std' => 'title',
                        // Default WC value
                        'save_always' => true,
                        'description' => sprintf( __( 'Select how to sort retrieved products. More at %s. Default by Title', 'stm-woocommerce-motors-auto-parts' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __( 'Sort order', 'stm-woocommerce-motors-auto-parts' ),
                        'param_name' => 'order',
                        'value' => $order_way_values,
                        'std' => 'ASC',
                        // default WC value
                        'save_always' => true,
                        'description' => sprintf( __( 'Designates the ascending or descending order. More at %s. Default by ASC', 'stm-woocommerce-motors-auto-parts' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __( 'Products Type', 'stm-woocommerce-motors-auto-parts' ),
                        'param_name' => 'product_type',
                        'value' => $productsType,
                        'std' => '',
                    ),
                    array(
                        'type' => 'hidden',
                        'param_name' => 'skus',
                    ),
                )) );

}
