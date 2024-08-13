<?php

define( 'CRUXDEVICE_VERSION', '1.0.1' );

add_shortcode( 'cruxdevice', function ( $atts = [] ) {
	wp_enqueue_style( 'cruxdevice', cruxdevice_assets() . 'css/main.css', [], CRUXDEVICE_VERSION );
	wp_enqueue_script( 'cruxdevice-vue', cruxdevice_assets() . 'js/vue.min.js', [], CRUXDEVICE_VERSION, true );
	wp_enqueue_script( 'cruxdevice-main', cruxdevice_assets() . 'js/main.js', [], CRUXDEVICE_VERSION, true );
	wp_localize_script( 'cruxdevice-main', 'cruxdevice_config', cruxdevice_config() );

	ob_start();
	include 'template.php';

	return ob_get_clean();
} );


function cruxdevice_config() {
	$base = rtrim( get_option( 'cruxdevice_host' ), '/' ) . '/';

	return [
		'base_uri'        => $base,
		'attachments_uri' => $base . 'attachments/',
		'api_uri'         => $base . 'api/',
		'username'        => get_option( 'cruxdevice_username' ),
		'password'        => get_option( 'cruxdevice_password' ),
	];
}


function cruxdevice_assets() {
	return get_stylesheet_directory_uri() . '/device/assets/';
}


add_action( 'admin_menu', function () {
	add_menu_page( 'Device wiring', 'Device wiring', 'edit_pages', 'cruxdevice', 'cruxdevice_admin' );
} );


function cruxdevice_admin() {
	$config = cruxdevice_config();
	$api_uri = $config['api_uri'];

	echo '<h1>' . get_admin_page_title() . '</h1>';

	$response = wp_remote_post( $api_uri . 'login', [
		'body' => [
			'username' => $config['username'],
			'password' => $config['password'],
		],
	] );
	if(!is_wp_error($response)){
		$auth = json_decode( $response['body'] );

		if ( ! isset( $auth->token ) ) {
			echo "Authentication error. HTTP/{$response['response']['code']}. Body: {$response['body']}";
			return;
		}

		echo '<iframe src="' . $config['base_uri'] . '#!/?token=' . $auth->token . '" width="100%" height="600px"></iframe>';
	}else{
		echo '<pre>';
			print_r($response);
		echo '</pre>';
	}
}


add_action( 'customize_register', function ( $wp_customize ) {
	/** @var WP_Customize_Manager $wp_customize */
	$wp_customize->add_section( 'cruxdevice', [
		'title' => 'Device API',
		'priority' => 400,
	] );

	$wp_customize->add_setting( 'cruxdevice_host', array(
		'type'              => 'option',
		'capability'        => 'manage_options',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'cruxdevice_host', array(
		'section' => 'cruxdevice',
		'type' => 'text',
		'label' => 'Host',
	));

	$wp_customize->add_setting( 'cruxdevice_username', array(
		'type'              => 'option',
		'capability'        => 'manage_options',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'cruxdevice_username', array(
		'section' => 'cruxdevice',
		'type' => 'text',
		'label' => 'Username',
	));

	$wp_customize->add_setting( 'cruxdevice_password', array(
		'type'              => 'option',
		'capability'        => 'manage_options',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'cruxdevice_password', array(
		'section' => 'cruxdevice',
		'type' => 'text',
		'label' => 'Password',
	) );

	$wp_customize->add_setting( 'cruxdevice_dont_see_page', array(
		'type'              => 'option',
		'capability'        => 'manage_options',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'cruxdevice_dont_see_page', array(
		'label' => 'Page for "Dont\' see"',
		'section' => 'cruxdevice',
		'type' => 'dropdown-pages',
		'allow_addition' => true,
	) );

	$wp_customize->add_setting( 'cruxdevice_help_page', array(
		'type'              => 'option',
		'capability'        => 'manage_options',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'cruxdevice_help_page', array(
		'label' => 'Page for "Connector help"',
		'section' => 'cruxdevice',
		'type' => 'dropdown-pages',
		'allow_addition' => true,
	) );

} );
