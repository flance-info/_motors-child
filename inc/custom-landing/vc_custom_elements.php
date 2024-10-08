<?php

if ( function_exists( 'vc_map' ) ) {
	add_action( 'init', 'register_child_custom_elements' );
}

function get_woocommerce_product_autocomplete_suggester($query) {
    global $wpdb;
    $query = esc_sql($query);

    // Query WooCommerce products
    $product_results = $wpdb->get_results($wpdb->prepare(
        "SELECT p.ID AS id, p.post_title AS title FROM {$wpdb->posts} AS p
        JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id
        WHERE p.post_type = 'product' AND p.post_status = 'publish'
        AND (p.ID = %d OR p.post_title LIKE '%%%s%%')",
        $query, $query
    ), ARRAY_A);

    $results = array();
    foreach ($product_results as $value) {
        $results[] = array(
            'value' => $value['id'],
            'label' => $value['title']
        );
    }
    return $results;
}
add_filter('vc_autocomplete_stm_wcmap_custom_products_list_products_ids_callback', 'get_woocommerce_product_autocomplete_suggester', 10, 1);

function render_woocommerce_product_autocomplete_suggester($query) {
    $query = trim($query['value']);
    if (!empty($query)) {
        $product = get_post((int) $query);
        if (is_object($product)) {
            return array(
                'value' => $product->ID,
                'label' => $product->post_title
            );
        }
    }
    return false;
}

add_filter('vc_autocomplete_stm_wcmap_custom_products_list_products_ids_render', 'render_woocommerce_product_autocomplete_suggester', 10, 1);


function register_child_custom_elements() {

	$order_by_values  = array(
		'',
		__( 'Date', 'stm-woocommerce-motors-auto-parts' )               => 'date',
		__( 'ID', 'stm-woocommerce-motors-auto-parts' )                 => 'ID',
		__( 'Author', 'stm-woocommerce-motors-auto-parts' )             => 'author',
		__( 'Title', 'stm-woocommerce-motors-auto-parts' )              => 'title',
		__( 'Modified', 'stm-woocommerce-motors-auto-parts' )           => 'modified',
		__( 'Random', 'stm-woocommerce-motors-auto-parts' )             => 'rand',
		__( 'Comment count', 'stm-woocommerce-motors-auto-parts' )      => 'comment_count',
		__( 'Menu order', 'stm-woocommerce-motors-auto-parts' )         => 'menu_order',
		__( 'Menu order & title', 'stm-woocommerce-motors-auto-parts' ) => 'menu_order title',
		__( 'Include', 'stm-woocommerce-motors-auto-parts' )            => 'include',
	);
	$order_way_values = array(
		'',
		__( 'Descending', 'stm-woocommerce-motors-auto-parts' ) => 'DESC',
		__( 'Ascending', 'stm-woocommerce-motors-auto-parts' )  => 'ASC',
	);
	$productsType     = array(
		'',
		esc_html__( 'New', 'stm-woocommerce-motors-auto-parts' ) => 'best_selling',
	);

	vc_map( array(
		'html_template' => get_stylesheet_directory() . '/vc_templates/stm_wcmap_products_list.php',
		'name'          => __( 'STM WC Custom Products', 'stm-woocommerce-motors-auto-parts' ),
		'base'          => 'stm_wcmap_custom_products_list',
		'icon'          => 'icon-wpb-woocommerce',
		'category'      => __( 'STM Auto Parts', 'stm-woocommerce-motors-auto-parts' ),
		'description'   => __( 'Show multiple products by ID or SKU.', 'stm-woocommerce-motors-auto-parts' ),
		'params'        => array(
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Columns', 'stm-woocommerce-motors-auto-parts' ),
				'param_name'  => 'columns',
				'value'       => array(
					4 => 4,
					3 => 3
				),
				'std'         => '4',
				'save_always' => true,
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Number Posts', 'js_composer' ),
				'value'       => 8,
				'param_name'  => 'number_posts',
				'save_always' => true,
			),
			     array(
                'type'        => 'autocomplete',
                'heading'     => __('Select Products', 'your-text-domain'),
                'param_name'  => 'products_ids',
                'settings'    => array(
                    'multiple'    => true,
                    'sortable'    => true,
                    'unique_values' => true,
                    'min_length'  => 2,
                    'groups'      => true,
                    'sortable'    => true,
                    'placeholder' => __('Search for products', 'your-text-domain'),
                ),
                'description' => __('Search and select multiple products by title or SKU.', 'your-text-domain'),
            ),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Order by', 'stm-woocommerce-motors-auto-parts' ),
				'param_name'  => 'orderby',
				'value'       => $order_by_values,
				'std'         => 'title',
				// Default WC value
				'save_always' => true,
				'description' => sprintf( __( 'Select how to sort retrieved products. More at %s. Default by Title', 'stm-woocommerce-motors-auto-parts' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Sort order', 'stm-woocommerce-motors-auto-parts' ),
				'param_name'  => 'order',
				'value'       => $order_way_values,
				'std'         => 'ASC',
				// default WC value
				'save_always' => true,
				'description' => sprintf( __( 'Designates the ascending or descending order. More at %s. Default by ASC', 'stm-woocommerce-motors-auto-parts' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Products Type', 'stm-woocommerce-motors-auto-parts' ),
				'param_name' => 'product_type',
				'value'      => $productsType,
				'std'        => '',
			),
			array(
				'type'       => 'hidden',
				'param_name' => 'skus',
			),
		)
	) );
	vc_map( array(
		'html_template' => get_stylesheet_directory() . '/vc_templates/stm_wcmap_customer_feedback.php',
		'name'          => __( 'STM WC Customer Feedbacks Widget', 'stm-woocommerce-motors-auto-parts' ),
		'base'          => 'stm_wcmap_customer_feedback',
		'icon'          => 'icon-wpb-woocommerce',
		'category'      => __( 'STM Auto Parts', 'stm-woocommerce-motors-auto-parts' ),
		'description'   => __( 'STM WC Customer Feedbacks', 'stm-woocommerce-motors-auto-parts' ),
		'params'        => array()
	) );
	vc_map( array(
		'html_template' => get_stylesheet_directory() . '/vc_templates/stm_wcmap_customer_faq.php',
		'name'          => __( 'STM WC FAQ Widget', 'stm-woocommerce-motors-auto-parts' ),
		'base'          => 'stm_wcmap_customer_faq',
		'icon'          => 'icon-wpb-woocommerce',
		'category'      => __( 'STM Auto Parts', 'stm-woocommerce-motors-auto-parts' ),
		'description'   => __( 'STM WC FAQs', 'stm-woocommerce-motors-auto-parts' ),
		'params'        => array()
	) );
	vc_map( array(
		'html_template' => get_stylesheet_directory() . '/vc_templates/stm_wcmap_slider.php',
		'name'          => __( 'STM Video Slider', 'stm-woocommerce-motors-auto-parts' ),
		'base'          => 'stm_wcmap_slider',
		'icon'          => 'icon-wpb-woocommerce',
		'category'      => __( 'STM Auto Parts', 'stm-woocommerce-motors-auto-parts' ),
		'description'   => __( 'STM Slider', 'stm-woocommerce-motors-auto-parts' ),
		'params'        => array(
			array(
				'type'        => 'param_group',
				'heading'     => __( 'Videos', 'stm-woocommerce-motors-auto-parts' ),
				'param_name'  => 'videos',
				'params'      => array(
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Video URL', 'stm-woocommerce-motors-auto-parts' ),
						'param_name'  => 'video_url',
						'description' => __( 'Enter the YouTube video URL', 'stm-woocommerce-motors-auto-parts' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Video Title', 'stm-woocommerce-motors-auto-parts' ),
						'param_name'  => 'video_title',
						'description' => __( 'Enter the title of the video', 'stm-woocommerce-motors-auto-parts' ),
					),
					array(
						'type'        => 'textarea',
						'heading'     => __( 'Video Description', 'stm-woocommerce-motors-auto-parts' ),
						'param_name'  => 'video_description',
						'description' => __( 'Enter a short description for the video', 'stm-woocommerce-motors-auto-parts' ),
					),
				),
				'value'       => '',
				'description' => __( 'Add multiple videos with their titles and descriptions.', 'stm-woocommerce-motors-auto-parts' ),
			),
		),
	) );


}

function register_woocommerce_categories_widget() {
	vc_map( array(
		'html_template' => get_stylesheet_directory() . '/vc_templates/stm_wcmap_category_products_list.php',
		'name'          => __( 'WooCommerce Part Categories', 'stm-woocommerce-motors-auto-parts' ),
		'base'          => 'stm_woocommerce_categories',
		'icon'          => 'icon-wpb-woocommerce',
		'category'      => __( 'STM Auto Parts', 'stm-woocommerce-motors-auto-parts' ),
		'description'   => __( 'Display a list of WooCommerce product categories.', 'stm-woocommerce-motors-auto-parts' ),
		'params'        => array(
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Number of Categories', 'stm-woocommerce-motors-auto-parts' ),
				'param_name'  => 'number',
				'value'       => 5,
				'description' => __( 'Enter the number of categories to display. Set to -1 to display all categories.', 'stm-woocommerce-motors-auto-parts' ),
				'save_always' => true,
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Order by', 'stm-woocommerce-motors-auto-parts' ),
				'param_name'  => 'orderby',
				'value'       => array(
					__( 'Name', 'stm-woocommerce-motors-auto-parts' )  => 'name',
					__( 'ID', 'stm-woocommerce-motors-auto-parts' )    => 'ID',
					__( 'Slug', 'stm-woocommerce-motors-auto-parts' )  => 'slug',
					__( 'Count', 'stm-woocommerce-motors-auto-parts' ) => 'count',
				),
				'std'         => 'name',
				'description' => __( 'Select how to sort categories.', 'stm-woocommerce-motors-auto-parts' ),
				'save_always' => true,
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Sort order', 'stm-woocommerce-motors-auto-parts' ),
				'param_name'  => 'order',
				'value'       => array(
					__( 'Ascending', 'stm-woocommerce-motors-auto-parts' )  => 'ASC',
					__( 'Descending', 'stm-woocommerce-motors-auto-parts' ) => 'DESC',
				),
				'std'         => 'ASC',
				'description' => __( 'Designates the ascending or descending order.', 'stm-woocommerce-motors-auto-parts' ),
				'save_always' => true,
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Columns', 'stm-woocommerce-motors-auto-parts' ),
				'param_name'  => 'columns',
				'value'       => array(
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
				),
				'std'         => 4,
				'description' => __( 'Select the number of columns to display categories in.', 'stm-woocommerce-motors-auto-parts' ),
				'save_always' => true,
			),
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Hide Empty', 'stm-woocommerce-motors-auto-parts' ),
				'param_name'  => 'hide_empty',
				'value'       => array( __( 'Yes', 'stm-woocommerce-motors-auto-parts' ) => 'yes' ),
				'std'         => 'yes',
				'description' => __( 'Hide categories that do not have any products.', 'stm-woocommerce-motors-auto-parts' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Show Count', 'stm-woocommerce-motors-auto-parts' ),
				'param_name'  => 'show_count',
				'value'       => array( __( 'Yes', 'stm-woocommerce-motors-auto-parts' ) => 'yes' ),
				'description' => __( 'Display the product count within each category.', 'stm-woocommerce-motors-auto-parts' ),
			),
			array(
				'type'        => 'autocomplete',
				'heading'     => __( 'Select Categories', 'stm-woocommerce-motors-auto-parts' ),
				'param_name'  => 'category_ids',
				'settings'    => array(
					'multiple'      => true,
					'sortable'      => true,
					'unique_values' => true,
					'min_length'    => 1,
					'query_value'   => 'category_id', // Internal ID to search categories
					'display_value' => 'name',        // Display the category name
				),
				'description' => __( 'Search and select categories to display.', 'stm-woocommerce-motors-auto-parts' ),
			),
		),
	) );
}

add_action( 'vc_before_init', 'register_woocommerce_categories_widget' );
function vc_autocomplete_stm_woocommerce_categories_category_ids_callback( $query ) {
	global $wpdb;
	$search  = esc_sql( $query );
	$results = $wpdb->get_results( "SELECT t.term_id as id, t.name as name
                                     FROM {$wpdb->terms} t
                                     INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
                                     WHERE tt.taxonomy = 'product_cat' AND t.name LIKE '%{$search}%' LIMIT 20" );
	$data    = array();
	if ( ! empty( $results ) ) {
		foreach ( $results as $result ) {
			$data[] = array(
				'value' => $result->id,
				'label' => $result->name,
			);
		}
	}

	return $data;
}

add_filter( 'vc_autocomplete_stm_woocommerce_categories_category_ids_callback', 'vc_autocomplete_stm_woocommerce_categories_category_ids_callback' );

