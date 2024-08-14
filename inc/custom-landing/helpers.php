<?php

function stm_enqueue_custom_styles() {

	// Enqueue Google Fonts
	//wp_enqueue_style( 'poppins-font', 'https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&display=swap', array(), STM_THEME_VERSION, 'all' );
	//wp_enqueue_style( 'helvetica-neue-font', 'https://fonts.googleapis.com/css2?family=Helvetica+Neue:wght@400;500&display=swap', array(), STM_THEME_VERSION, 'all' );
	//wp_enqueue_style( 'poppins-semibold-font', 'https://fonts.googleapis.com/css2?family=Poppins+SemiBold:wght@600&display=swap', array(), STM_THEME_VERSION, 'all' );
	//wp_enqueue_style( 'roboto-font', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap', array(), STM_THEME_VERSION, 'all' );
	//wp_enqueue_style( 'inter-font', 'https://fonts.googleapis.com/css2?family=Inter:wght@400&display=swap', array(), STM_THEME_VERSION, 'all' );
	// Enqueue Bootstrap
	//wp_enqueue_style( 'bootstrap-css', 'https://cruxinterfacing.com/wp-content/themes/motors/assets/css/bootstrap.min.css?ver=1.0', array(), STM_THEME_VERSION, 'all' );
	// Enqueue local CSS file
	wp_enqueue_style( 'custom-index-css', get_stylesheet_directory_uri() . '/assets/css/custom-css/index.css', array(), time(), 'all' );

}

function stm_check_page_template_and_enqueue_styles() {
	if ( is_page_template( 'custom-landing.php' ) ) {
		add_action( 'wp_enqueue_scripts', 'stm_enqueue_custom_styles', 1000 );
	}
}

add_action( 'wp', 'stm_check_page_template_and_enqueue_styles' );

include_once "vc_custom_elements.php";

class Custom_Walker_Nav_Menu extends Walker_Nav_Menu {

    // Start Level - for <ul> sub-menus
    function start_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat("\t", $depth);
        $submenu = ($depth > 0) ? ' sub-sub-menu' : ' sub-menu';
        $output .= "\n$indent<div class=\"submenu-container$submenu depth_$depth\"><ul class=\"sub-menu\">\n";
    }

    // End Level - for </ul> sub-menus
    function end_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul></div>\n";
    }

    // Start Element - for <li> menu items
    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $indent = ( $depth ) ? str_repeat("\t", $depth) : '';
        $li_attributes = '';
        $class_names = $value = '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = ($args->walker->has_children) ? 'dropdown' : '';
        $classes[] = 'menu-item-' . $item->ID;
        if( $depth && $args->walker->has_children ) {
            $classes[] = 'dropdown-submenu';
        }

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = ' class="' . esc_attr( $class_names ) . '"';

        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
        $id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= $indent . '<li' . $id . $value . $class_names . $li_attributes .'>';

        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';

        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    // End Element - for </li> menu items
    function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= "</li>\n";
    }
}


