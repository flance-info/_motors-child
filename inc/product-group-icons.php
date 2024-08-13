<?php

add_action( 'add_meta_boxes_product', 'meta_box_for_products' );
function meta_box_for_products( $post ){
	add_meta_box( 'product_group_icons_id', __( 'Group Icons' ), 'product_group_icons_render', 'product', 'normal', 'low' );
}

function product_group_icons_render( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'product_group_icons_nonce' ); //used later for security

	$icons = product_group_icons_list();
	?>
	<ul class="product-group-icons-wr">
		<?php $val = get_post_meta($post->ID, 'product_group_icons', true); ?>
		<?php foreach ($icons as $key => $icon): ?>
			<?php $checked = !empty($val[$key]) && $val[$key] == '1' ? true : false; ?>
			<li class="<?php echo $checked ? 'icon-selected' : '' ?>">
				<input id="icon-<?php echo $key ?>" type="checkbox" name="product_group_icons[<?php echo $key ?>]"
					value="1"
					<?php echo $checked ? 'checked' : '' ?> />
				<label for="icon-<?php echo $key ?>">
					<img src="<?php echo $icon['icon'] ?>" alt="<?php echo $icon['title'] ?>">
				</label>
			</li>
		<?php endforeach; ?>
	</ul>

	<style>
		.product-group-icons-wr{
			display: flex;
			flex-direction: row;
			flex-wrap: wrap;
		}
		.product-group-icons-wr li{
			position: relative;
			padding: 5px;
			padding-left: 25px;
			border: 1px solid #dadada;
			margin-right: 10px;
			border-radius: 10px;
			background-color: #fafafa;
		}
		.product-group-icons-wr li.icon-selected {
				background-color: #6c98e1;
		}
		.product-group-icons-wr li input{
			vertical-align: middle;
			display: inline-block;
			position: absolute;
			top: 0;
			bottom: 0;
			margin: auto;
			left: 5px;
		}
		.product-group-icons-wr li img{
			height: 130px;
			width: auto;
			object-fit: contain;
		}
	</style>
	<?php
}

add_action( 'save_post_product', 'product_group_icons_save_meta_boxes_data', 10, 2 );
function product_group_icons_save_meta_boxes_data( $post_id ){
	// check for nonce to top xss
	if ( !isset( $_POST['product_group_icons_nonce'] ) || !wp_verify_nonce( $_POST['product_group_icons_nonce'], basename( __FILE__ ) ) ){
		return;
	}

	// check for correct user capabilities - stop internal xss from customers
	if ( ! current_user_can( 'edit_post', $post_id ) ){
		return;
	}

	// update fields
	if ( isset( $_REQUEST['product_group_icons'] ) ) {
		update_post_meta( $post_id, 'product_group_icons', $_POST['product_group_icons'] );
	}
}

add_action( 'woocommerce_after_single_product', 'product_group_icons_front_view', 1000 );
function product_group_icons_front_view( ) {
	global $product;

	// If the WC_product Object is not defined globally
	if ( ! is_a( $product, 'WC_Product' ) ) {
		$product = wc_get_product( get_the_id() );
	}

	$icons = product_group_icons_list();
	?>
	<ul class="product-group-icons-wr">
		<?php $val = get_post_meta($product->get_id(), 'product_group_icons', true); ?>
		<?php foreach ($icons as $key => $icon): ?>
			<?php $checked = !empty($val[$key]) && $val[$key] == '1' ? true : false; ?>
			<?php if(!$checked) continue; ?>
		  	<li>
				<img src="<?php echo $icon['icon'] ?>" alt="<?php echo $icon['title'] ?>">
			</li>
		<?php endforeach; ?>
	</ul>

	<style>
		.product-group-icons-wr{
			display: flex;
			flex-direction: row;
			flex-wrap: wrap;
			margin: 0 0 30px;
			padding: 0;
		}
		.product-group-icons-wr li{
			padding: 5px;
			list-style: none;
		}
		.product-group-icons-wr li img{
			HEIGHT: 130PX;
			WIDTH: AUTO;
			OBJECT-FIT: CONTAIN;
		}
	</style>
	<?php
}

function product_group_icons_list() {
	return 	[
		'retain-swc-controls' => [
			'icon' => get_stylesheet_directory_uri() . '/assets/product-group-icons/Picture1.jpg',
			'title' => 'Retail SWC Controls'
		],
		'reverse' => [
			'icon' => get_stylesheet_directory_uri() . '/assets/product-group-icons/Picture2.jpg',
			'title' => 'Reverse'
		],
		'accessory' => [
			'icon' => get_stylesheet_directory_uri() . '/assets/product-group-icons/Picture3.jpg',
			'title' => 'Accessory'
		],
		'antenna-retention' => [
			'icon' => get_stylesheet_directory_uri() . '/assets/product-group-icons/Picture4.jpg',
			'title' => 'Antenna Retention'
		],
		'usb-retention' => [
			'icon' => get_stylesheet_directory_uri() . '/assets/product-group-icons/Picture5.jpg',
			'title' => 'USB Retention'
		],
		'voice-control' => [
			'icon' => get_stylesheet_directory_uri() . '/assets/product-group-icons/Picture6.jpg',
			'title' => 'Voice Control'
		],
		'plug-and-play' => [
			'icon' => get_stylesheet_directory_uri() . '/assets/product-group-icons/Picture7.jpg',
			'title' => 'Plug & Play'
		],
		'phone' => [
			'icon' => get_stylesheet_directory_uri() . '/assets/product-group-icons/Picture8.jpg',
			'title' => 'Phone'
		],
		'amplifier' => [
			'icon' => get_stylesheet_directory_uri() . '/assets/product-group-icons/Picture9.jpg',
			'title' => 'Amplifier'
		],
		'can-bus' => [
			'icon' => get_stylesheet_directory_uri() . '/assets/product-group-icons/Picture10.jpg',
			'title' => 'CAN Bus'
		],
		'aux-retention' => [
			'icon' => get_stylesheet_directory_uri() . '/assets/product-group-icons/Picture11.jpg',
			'title' => 'AUX Retention'
		],
		'camera-retention' => [
			'icon' => get_stylesheet_directory_uri() . '/assets/product-group-icons/Picture12.jpg',
			'title' => 'Camera Retention'
		],
		'illumination' => [
			'icon' => get_stylesheet_directory_uri() . '/assets/product-group-icons/Picture13.jpg',
			'title' => 'Illumination'
		],
		'analog' => [
			'icon' => get_stylesheet_directory_uri() . '/assets/product-group-icons/Picture14.jpg',
			'title' => 'Analog'
		],
		'parking-brake' => [
			'icon' => get_stylesheet_directory_uri() . '/assets/product-group-icons/Picture15.jpg',
			'title' => 'Parking Brake'
		],
		'vcc' => [
			'icon' => get_stylesheet_directory_uri() . '/assets/product-group-icons/Picture16.jpg',
			'title' => 'VSS'
		],
		'onstar-retention' => [
			'icon' => get_stylesheet_directory_uri() . '/assets/product-group-icons/Picture17.jpg',
			'title' => 'OnStar Retention'
		],
		'starlink-retention' => [
			'icon' => get_stylesheet_directory_uri() . '/assets/product-group-icons/Picture18.jpg',
			'title' => 'Starlink Retention'
		],
	];
}



function import_product_group_icons() {
	$icons = product_group_icons_list();
	$table = [
		'SWRAD-55'			=> '1	1	1	0	0	0	1	0	1	1	0	0	1	0	0	1	0	0',
		'SWRBM-57'			=> '1	1	1	0	0	0	1	0	1	1	0	0	1	0	0	1	0	0',
		'SWRBM-57K'			=> '1	0	0	0	1	0	1	0	1	0	0	0	0	1	0	0	0	0',
		'SOOCR-26'			=> '0	1	1	0	0	1	1	1	1	1	0	0	1	0	0	1	0	0',
		'SWRCR-59'			=> '1	1	1	0	0	1	1	1	1	1	0	0	1	0	0	1	0	0',
		'SWRCR-59D'			=> '1	1	1	0	0	1	1	1	0	1	1	1	1	0	0	1	0	0',
		'SWRFT-53'			=> '1	1	1	0	0	0	1	1	0	1	0	0	1	0	0	1	0	0',
		'SOOFD-27'			=> '0	1	1	0	0	0	1	0	0	1	0	0	1	0	1	1	0	0',
		'SOOFD-27C'			=> '0	1	1	0	0	0	1	0	0	1	0	0	1	0	1	1	0	0',
		'SWRFD-60'			=> '1	1	1	0	0	0	1	1	0	1	1	0	1	1	0	1	0	0',
		'SWRFD-60B'			=> '1	1	1	0	0	0	1	1	0	1	1	0	1	0	0	1	0	0',
		'SWRFD-60L'			=> '1	0	0	1	0	0	1	1	0	0	1	0	1	1	0	1	0	0',
		'SWRFD-60E'			=> '1	1	1	0	0	1	1	1	0	1	1	0	1	0	0	1	0	0',
		'SWRFD-60T'			=> '1	1	1	0	0	0	1	1	1	1	0	1	1	0	0	1	0	0',
		'SONGM-11'			=> '0	1	1	1	0	0	1	0	1	1	0	0	1	0	1	1	1	0',
		'SOOGM-15'			=> '1	1	1	1	0	1	1	1	0	1	0	1	1	0	1	0	1	0',
		'SOOGM-15V'			=> '1	1	1	1	0	1	1	1	0	1	0	1	1	0	1	0	1	0',
		'SOOGM-16'			=> '1	1	1	1	0	1	1	1	1	1	0	1	1	0	1	0	1	0',
		'SOOGM-16V'			=> '1	1	1	1	0	1	1	1	0	1	0	1	1	0	1	0	1	0',
		'SOOGM-16W'			=> '1	1	1	1	0	1	1	1	1	1	0	1	1	0	1	0	1	0',
		'SOOGM-16B'			=> '1	1	1	1	0	1	1	1	1	1	0	1	1	0	1	1	1	0',
		'SOOGM-19L'			=> '1	1	1	1	0	1	1	1	0	1	1	1	1	0	1	1	1	0',
		'SOOGM-19N'			=> '1	1	1	1	0	1	1	1	0	1	0	1	1	0	1	0	1	0',
		'SOCGM-17'			=> '0	0	1	0	0	0	1	0	0	1	0	0	0	0	0	0	0	0',
		'SOCGM-17B'			=> '0	0	1	0	0	0	1	0	0	1	0	0	1	0	0	0	0	0',
		'SOCGM-17C'			=> '0	1	1	0	0	0	1	0	0	1	0	0	1	0	0	0	0	0',
		'SOCGM-18'			=> '0	1	1	1	0	0	1	0	0	1	0	1	1	0	0	0	0	0',
		'SOCGM-18B'			=> '0	1	1	1	0	0	1	0	0	1	0	0	1	0	0	0	0	0',
		'SOCGM-18L'			=> '0	1	1	1	0	0	1	0	0	1	0	0	1	0	0	0	0	0',
		'SWRGM-48'			=> '1	1	1	0	0	1	1	1	1	1	0	0	1	0	0	0	0	0',
		'SWRGM-49'			=> '1	1	1	1	0	1	1	1	0	1	0	1	1	0	0	0	0	0',
		'SWRGM-49A'			=> '1	1	1	1	0	1	1	1	0	1	0	1	1	0	0	0	0	0',
		'SWRGM-49L'			=> '1	1	1	1	0	1	1	1	0	1	0	1	1	0	0	0	0	0',
		'SWRGM-49N'			=> '1	1	1	1	0	1	1	1	0	1	0	1	1	0	0	0	0	0',
		'SWRGM-49R'			=> '1	1	1	0	0	1	1	1	0	1	0	1	1	0	0	0	0	0',
		'SWRGM-49S'			=> '1	1	1	0	0	1	1	1	0	1	0	0	1	0	0	0	0	0',
		'SWRGM-51'			=> '1	1	1	1	0	1	1	1	1	1	0	0	1	0	0	0	0	0',
		'HBRHD-61A'			=> '1	0	0	0	0	1	1	1	0	1	0	0	0	0	0	0	0	0',
		'SWRHK-65E'			=> '1	0	0	0	0	1	1	1	0	0	1	0	1	1	0	1	0	0',
		'SWRHK-65P'			=> '1	0	0	1	0	1	1	1	0	0	1	0	1	1	0	1	0	0',
		'SWRHK-65Q'			=> '1	1	0	1	1	1	1	1	0	0	1	1	1	1	1	1	0	0',
		'SWRHK-65S'			=> '1	0	0	1	0	1	1	1	0	0	1	0	1	1	0	1	0	0',
		'SWRHK-65T'			=> '1	0	0	1	0	0	1	1	0	0	0	0	1	1	1	1	0	0',
		'SWRHN-62B'			=> '1	0	0	1	0	0	1	1	0	0	1	0	1	1	0	1	0	0',
		'SWRHN-62C'			=> '1	1	1	1	0	0	1	1	0	1	0	1	1	1	0	0	0	0',
		'SWRHN-62D'			=> '1	0	0	1	0	0	1	1	0	0	0	0	1	1	0	0	0	0',
		'SWRHN-62L'			=> '1	0	0	1	0	0	1	1	0	0	0	0	1	1	0	0	0	0',
		'SWRMZ-64C'			=> '1	0	0	0	0	0	1	1	1	0	0	0	1	1	0	0	0	0',
		'SWRMB-57M'			=> '1	1	1	0	0	0	1	1	0	1	0	0	1	0	0	1	0	0',
		'SWRNS-63S'			=> '1	1	1	1	0	0	1	1	0	0	0	1	1	0	0	1	0	0',
		'SWRNS-63T'			=> '1	0	0	0	0	0	1	1	0	0	0	0	1	1	0	0	0	0',
		'SWRNS-63U'			=> '1	0	0	1	0	0	1	1	0	0	0	0	1	1	0	1	0	0',
		'SWRSB-58'			=> '1	1	1	0	0	0	1	1	0	1	0	0	1	0	0	1	0	0',
		'SWRSU-38A'			=> '1	0	0	0	0	0	1	1	0	0	0	0	1	1	0	0	0	0',
		'SWRSU-38B'			=> '1	0	0	0	0	0	1	1	0	0	0	0	1	1	0	0	0	0',
		'SWRSU-38C'			=> '1	1	0	1	0	0	1	1	0	0	1	1	1	1	0	0	0	1',
		'SOHTL-20'			=> '1	0	0	0	0	1	0	1	1	0	1	1	0	0	0	0	0	0',
		'SWRTY-61C'			=> '1	1	0	0	1	0	1	1	1	1	1	1	1	1	1	1	0	0',
		'SWRTY-61N'			=> '1	1	0	1	1	0	1	1	0	0	1	1	1	1	1	1	0	0',
		'SWRTY-61J'			=> '1	1	0	0	0	0	1	1	1	0	1	1	0	1	1	1	0	0',
		'SWRTY-61P'			=> '1	1	0	1	1	0	1	1	1	1	1	1	1	1	1	1	0	0',
		'SWRTY-61S'			=> '1	0	0	0	0	0	1	1	0	0	1	0	0	1	0	0	0	0',
		'SOCVW-21'			=> '1	1	0	0	0	1	0	1	1	0	0	1	0	0	1	0	0	0',
		'SWRVW-52'			=> '1	1	1	0	0	0	1	1	1	1	0	0	1	0	0	0	0	0',
		'SWRVW-52B'			=> '1	1	1	0	0	0	1	1	1	1	0	0	1	0	0	1	0	0',
		'SWRVL-54'			=> '1	1	1	0	0	0	1	1	1	1	0	0	1	0	0	1	0	0',
		'SWR-100'			=> '1	0	1	0	0	0	1	0	0	1	0	0	0	1	0	0	0	0',
		'SWR-A'				=> '1	0	0	0	0	0	1	0	0	0	0	0	0	1	0	0	0	0'
	];


	foreach ($table as $name => $item) {
		$values = explode("	", $item);
		echo("<h3>{$name}</h3>");
		$posts = stm_get_product_by_codename($name);

		$list = [];

		echo("<p><ul>");
		foreach (array_keys($icons) as $k => $icon) {
		  if($values[$k] == '1'){
			  echo("<li>{$icons[$icon]['title']}</li>");
			  $list[$icon] = 1;
		  }
		}
		echo("</ul></p>");

		if(!empty($posts)){
			foreach ($posts as $post) {
				echo("<p style='margin-bottom: 5px;margin-top: 5px'>Applied to <strong>\"{$post->post_title}\" (id: {$post->id})</strong></p>");
			 	update_post_meta( $post->id, 'product_group_icons', $list );
//			  var_dump($list);
			}
		}
	}
}

function stm_get_product_by_codename($codename) {
	global $wpdb;
	$sql = $wpdb->prepare("SELECT post_title,id FROM $wpdb->posts WHERE post_title LIKE %s", "$codename%");
	return $wpdb->get_results($sql);
}

add_action('template_redirect', function() {
	if(isset($_REQUEST['import-new-icons']) && is_super_admin(get_current_user_id())) {
		import_product_group_icons();
		die;
	}
});