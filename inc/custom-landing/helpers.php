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

