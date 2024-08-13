<?php

add_action('saved_term', 'stm_delete_update_term_hook');
add_action('delete_term', 'stm_delete_update_term_hook');
function stm_delete_update_term_hook() {
	delete_transient('terms_hierarchy');
	delete_transient('binding_hierarchy');
}
function get_term_array($post_id, $tax_name){
	$temp = [];
	$categories = stm_get_cat_hierarchy();
	$_temp = wp_get_post_terms( $post_id,  $tax_name, ['fields' => 'names'] );

	foreach ($categories as $name => $category) {

		if(!empty($category) && is_array($category)){
			if(in_array($name, $_temp)) $temp[] = $name;
			foreach ($category as $sub_name => $subcategory) {
				if(in_array($subcategory, $_temp)) $temp[] = $subcategory;
				if(!empty($subcategory) && is_array($subcategory)){
					foreach ($subcategory as $_name => $_category) {
						if(empty($_category)){
							if(in_array($_category, $_temp)) $temp[$name][$subcategory][] = $_category;
						}
					}
				}else{
					if(in_array($subcategory, $_temp)) $temp[$subcategory] = $name;
				}
			}
		}else{
			if(in_array($name, $_temp)) $temp[] = $name;
		}
	}
	return $temp;
}

function stm_get_cat_hierarchy($tax = 'product_cat') {
	$args = array(
		'taxonomy' => $tax,
		'parent' => 0,
		'hide_empty' => false
	);
	$arr = [];
	$next = get_terms($args);
	if ($next) {
		foreach ($next as $cat) {
			$children = get_terms( ['child_of' => $cat->term_id, 'taxonomy' => $tax, 'hide_empty' => false]);
			if( !empty( $children ) ) {
				foreach ($children as $child) {
					$arr[$cat->name][$child->name] = $child->name;
				}
			}else{
				$arr[$cat->name] = [];
			}
		}
	}
	return $arr;
}

/**
 * @return array
 */
function stm_get_terms_hierarchy() {
	$terms_hierarchy = get_transient('terms_hierarchy');
	if($terms_hierarchy) return $terms_hierarchy;
	$bindings = get_terms( ['taxonomy' => 'custom-binding', 'hide_empty' => false] );
	$arr = [];
	foreach ($bindings as $binding) {
		$_make = get_term_meta($binding->term_id, 'make', true);
		if($_make){
			$make = get_term_by('slug', $_make, 'pa_make');
			$_model = get_term_meta($binding->term_id, 'models', true);
			if($_model){
				$model = get_term_by('slug', $_model, 'pa_model');
				$_year = get_term_meta($binding->term_id, 'year');
				if($_year){
					foreach ($_year as $y) {
						$year = get_term_by('slug', $y, 'pa_part-year');
						if(!empty($make->name) && !empty($model->name) && !empty($year->name)){
							$_make = ucwords($make->name, "- \t\r\n\f\v'");
							$_model = ucwords($model->name, "- \t\r\n\f\v'");
							$_year = ucwords($year->name, "- \t\r\n\f\v'");
							$arr[$_make][$_model][$_year] = $_year;
						}
					}
				}else{
					if(!empty($make->name) && !empty($model->name)){
						$_make = ucwords($make->name, "- \t\r\n\f\v'");
						$_model = ucwords($model->name, "- \t\r\n\f\v'");
						$arr[$_make][$_model] = [];
					}
				}
			}else{
				if(!empty($make->name)){
					$_make = ucwords($make->name, "- \t\r\n\f\v'");
					$arr[$_make] = [];
				}
			}
		}
	}
	set_transient('terms_hierarchy', $arr);
	return $arr;
}

function stm_get_binding_hierarchy() {
	$binding_hierarchy = get_transient('binding_hierarchy');
	if($binding_hierarchy) return $binding_hierarchy;
	$bindings = get_terms( ['taxonomy' => 'custom-binding', 'hide_empty' => true] );
	$arr = [];
	foreach ($bindings as $binding) {
		$_make = get_term_meta($binding->term_id, 'make', true);
		$_model = get_term_meta($binding->term_id, 'models', true);
		$_year = get_term_meta($binding->term_id, 'year');

		if($_make){
			$make = get_term_by('slug', $_make, 'pa_make');
			$arr['makes'][ucwords($make->name, "- \t\r\n\f\v'")] = [
				'name' => ucwords($make->name, "- \t\r\n\f\v'"),
				'slug' => $make->slug,
			];
			if($_model){
				$model = get_term_by('slug', $_model, 'pa_model');
				$arr['models'][ucwords($make->name, "- \t\r\n\f\v'")][ucwords($model->name, "- \t\r\n\f\v'")] = [
					'name' => ucwords($model->name, "- \t\r\n\f\v'"),
					'slug' => $model->slug,
				];
				if($_year){
					foreach ($_year as $y) {
						$year = get_term_by('slug', $y, 'pa_part-year');
						$arr['years'][ucwords($make->name, "- \t\r\n\f\v'")][ucwords($model->name, "- \t\r\n\f\v'")][ucwords($year->name, "- \t\r\n\f\v'")] = [
							'name' => ucwords($year->name, "- \t\r\n\f\v'"),
							'slug' => $year->slug,
						];
					}
				}
			}
		}
	}
	set_transient('binding_hierarchy', $arr);
	return $arr;
}


function algolia_convert_hierarchy_categories($arr) {
	$lvl0 = $lvl1 = $lvl2 = [];
	$mk = $md = $yr = [];
	foreach ($arr as $make => $model_year) {
		$lvl0[] = $make;
		$mk[$make] = $make;
		if(!empty($model_year) && is_array($model_year)){
			foreach ($model_year as $model => $_year) {
				$lvl1[] = "$make > $model";
				$md[$model] = $model;
				if(!empty($_year)){
					foreach ($_year as $year) {
						$lvl2[] = "$make > $model > $year";
						$yr[$year] = $year;
					}
				}
			}
		}
	}
	return [
		'lvl0' => $lvl0,
		'lvl1' => $lvl1,
		'lvl2' => $lvl2,
		'mk' => array_values($mk),
		'md' => array_values($md),
		'yr' => array_values($yr)
	];
}

function algolia_terms_hierarchy_object($post_id) {
	$bindings = wp_get_post_terms( $post_id,  'custom-binding' );
	$arr = [];
	foreach ($bindings as $binding) {
		$_make = get_term_meta($binding->term_id, 'make', true);
		if($_make){
			$make = get_term_by('slug', $_make, 'pa_make');
			$_model = get_term_meta($binding->term_id, 'models', true);
			if($_model){
				$model = get_term_by('slug', $_model, 'pa_model');
				$_year = get_term_meta($binding->term_id, 'year');
				if($_year){
					foreach ($_year as $y) {
						$year = get_term_by('slug', $y, 'pa_part-year');
						$arr[ucwords($make->name, "- \t\r\n\f\v'")][ucwords($model->name, "- \t\r\n\f\v'")][] = ucwords($year->name, "- \t\r\n\f\v'");
					}
				}else{
					$arr[ucwords($make->name, "- \t\r\n\f\v'")][ucwords($model->name, "- \t\r\n\f\v'")] = [];
				}
			}else{
				$arr[ucwords($make->name, "- \t\r\n\f\v'")] = [];
			}
		}
	}
	return $arr;
}

function algolia_category_hierarchy_object($post_id) {
	$cat_ids = wp_get_post_terms( $post_id,  'product_cat', ['fields' => 'ids'] );
	$args = array(
		'taxonomy' => 'product_cat',
		'parent' => 0,
		'hide_empty' => false
	);
	$arr = [];
	$next = get_terms($args);
	if ($next) {
		foreach ($next as $cat) {
			$children = get_terms( ['child_of' => $cat->term_id, 'taxonomy' => 'product_cat', 'hide_empty' => false]);
			if( !empty( $children ) ) {
				$class[] = 'has_child';
				if(in_array($cat->term_id, $cat_ids)) $arr[$cat->name] = [];
				foreach ($children as $child) {
					$class = [];
					if(in_array($child->term_id, $cat_ids)) $arr[$cat->name][$child->name] = [];
				}
			}else{
				if(in_array($cat->term_id, $cat_ids)) $arr[$cat->name] = $cat->name;
			}
		}
	}
	return $arr;

}


add_filter('post_to_record', 'algolia_post_to_record');
function algolia_post_to_record(WP_Post $post) {
	if(!is_admin()) return $post;
	$img_id = get_post_meta($post->ID, '_thumbnail_id', true);
	$gallery = get_post_meta($post->ID, '_product_image_gallery',true);
	$size = 'stm-img-796-466';
	$thumbnail[] = wp_get_attachment_image_src($img_id, $size)[0];
	$gallery_photos = getPostGalleryUrls($gallery, $size);
	$car_photos = array_merge($thumbnail,$gallery_photos);

	if(is_admin()){
		$location = get_post_meta($post->ID, 'stm_car_location', true);
		update_post_meta($post->ID, 'stm_city_car_admin', $location);
	}

	$arr = algolia_terms_hierarchy_object($post->ID);
	$cats = algolia_convert_hierarchy_categories($arr);

	$obj = algolia_category_hierarchy_object($post->ID);
	$_cats = algolia_convert_hierarchy_categories($obj);

	$product_cat = array_merge($_cats['mk'], $_cats['md']);
	$terms = array_merge($cats['mk'], $cats['md'], $cats['yr']);


	$val = [
		'objectID' => implode('#', [$post->post_type, $post->ID]),
		'title' => $post->post_title,
		'author' => [
			'id' => $post->post_author,
			'name' => get_user_by( 'ID', $post->post_author )->display_name,
		],
		'excerpt' => $post->post_excerpt,
		'content' => strip_tags($post->post_content),
		'url' => get_post_permalink($post->ID),
		'post_id' => $post->ID,
		'for_faceting' => [
			'product_cat' 	 => $product_cat,
			'terms' 		 => $terms,
			'make'			 => $cats['mk'],
			'model'			 => $cats['md'],
			'year'			 => $cats['yr'],
		],

		'product_cat' 	=> reset($product_cat),
		'terms' => $terms,

		'thumbnail' => wp_get_attachment_image_src($img_id, 'stm-img-796-466')[0],
		'price' => (float)get_post_meta($post->ID, 'price', true),
		'date' => (float)get_the_date('U', $post->ID),
		'galleryUrls' => $car_photos,

		"terms.lvl0" => $cats['lvl0'],
		"terms.lvl1" => $cats['lvl1'],
		"terms.lvl2" => $cats['lvl2'],

		'make'			  => $cats['mk'],
		'model'			  => $cats['md'],
		'year'			  => $cats['yr'],
	];
	stm_put_log('algolia_post_to_record', $val);
	return $val;
}


function algolia_terms_hierarchy() {
	$taxonomies = get_object_taxonomies( 'product', '' );
	$taxonomies = wp_filter_object_list( $taxonomies, [ 'hierarchical' => true ] );

	$terms = get_terms( [ 'taxonomy' => array_keys( $taxonomies ), 'hide_empty' => false ] );
	$terms = wp_list_pluck( $terms, 'name', 'term_id' );

	$result = [];
	foreach ( array_keys( $taxonomies ) as $taxonomy ) {
		$result[ $taxonomy ] = [];
		foreach ( _get_term_hierarchy( $taxonomy ) as $parent_id => $children_ids ) {
			$parent = $terms[ $parent_id ];
			foreach ( $children_ids as $term_id ) {
				$result[ $taxonomy ][ $terms[ $term_id ] ] = $parent;
			}
		}
	}
	$result['terms'] = stm_get_terms_hierarchy();
	return $result;
}


// add_action( 'publish_product', 'stm_activation_save_product', 10, 3 );
add_action( 'save_post_product', 'stm_activation_save_product', 10, 3 );
function stm_activation_save_product($id, WP_Post $post, $update) {

	if (wp_is_post_revision( $post) || wp_is_post_autosave( $post ) || $post->post_status == 'auto-draft') {
		return;
	}

	stm_put_log('algolia_wp_schedule_single_event', $id, "WORK SCHEDULE ({$post->post_status})");

	if('trash' == $post->post_status) {
		$res = algolia_update_post($id, $post, $update);
	} else {
	$timeout = 5; // Timeout on upload image via front, without timeout on delete post (Fix undelete post from algolia)
		// if('trash' == $post->post_status) $timeout = 0;
		$res = wp_schedule_single_event(
			time() + $timeout,
			'algolia_update_post_event',
			array($id, $post, $update)
		);
	}

	// $res = algolia_update_post($id, $post, $update);
	if($res !== false) stm_put_log('algolia_wp_schedule_single_event', $id, "WORKED SCHEDULE");
	else stm_put_log('algolia_wp_schedule_single_event', $id, "CANCELLED SCHEDULE");
}

add_action( 'algolia_update_post_event','algolia_update_post', 10, 3 );
function algolia_update_post($id, WP_Post $post, $update) {

	if (wp_is_post_revision( $id) || wp_is_post_autosave( $id )) {
		return $post;
	}

	global $algolia;

	$record = (array) apply_filters('post_to_record', $post);

	stm_put_log('algolia_update_post', $record, "record");

	if (! isset($record['objectid'])) {
		$record['objectid'] = implode('#', [$post->post_type, $id]);
	}
	stm_put_log('algolia_update_post', $record['objectid'], "record id");


	$index = $algolia->initindex(
		apply_filters('algolia_index_name', 'product')
	);

	if ('trash' == $post->post_status || 'draft' == $post->post_status || 'pending' == $post->post_status) {
		$res = $index->deleteobject($record['objectid']);
		stm_put_log('algolia_delete_post', $record['objectid'], "delete object");
		stm_put_log('algolia_delete_post', $res, "response");
	} else {
		$res = $index->saveobject($record);
		stm_put_log('algolia_update_post', $record['objectid'], "save object");
		stm_put_log('algolia_update_post', $res, "object");
	}
	return $post;
}







add_action('template_redirect', 'stm_algolia_reindex_product');
function stm_algolia_reindex_product() {
	if(!is_super_admin()) return;
	if(isset($_REQUEST['reindex_product'])){
		$records = algolia_reindex_product_callback();
		var_dump($records);
		die;
	}
}
function algolia_reindex_product_callback() {
	global $algolia;
	$index = $algolia->initIndex(
		apply_filters('algolia_index_name', 'product')
	);

	$index->clearObjects()->wait();

	$count = 0;

	$posts = new WP_Query([
		'posts_per_page' => -1,
		'post_type' => 'product',
		'post_status' => 'publish',
	]);

	if (! $posts->have_posts()) {
		exit;
	}

	$records = [];
	foreach ($posts->posts as $post) {

		$record = (array) apply_filters('post_to_record', $post);

		if (! isset($record['objectID'])) {
			$record['objectID'] = implode('#', [$post->post_type, $post->ID]);
		}

		$records[] = $record;
		$count++;
	}


	//$records = [(array) apply_filters('post_to_record', get_post(16322))];
	$index->saveObjects($records);

	return $records;
}


// Delete attachments on delete post
add_action( 'before_delete_post', 'stm_delete_attach_product', 5 );
function stm_delete_attach_product( $post_id ){
	if(get_post_type($post_id) !== 'product') return;
	$featId = get_post_thumbnail_id($post_id);
	$attachIds = get_post_meta($post_id, "gallery");
	if(!empty($featId)) {
		wp_delete_attachment($featId, true);
	}
	if(isset($attachIds[0])) {
		foreach ($attachIds[0] as $k => $val) {
			wp_delete_attachment($val, true);
		}
	}
}



add_action('wp_enqueue_scripts', 'algolia_load_assets');
function algolia_load_assets() {
	wp_register_script('slide-panel', get_stylesheet_directory_uri() . '/assets/js/slide-in-panel.js', ['jquery']);

	$clientPath = '/assets/js/vendor/algoliasearchLite.min.js';
	$isPath = '/assets/js/vendor/instantsearch.production.min.js';

	// create version number based for the last time the file was modified
	$clientVersion  = date("ymd-Gis", filemtime( get_stylesheet_directory() . $clientPath ));
	$isVersion  = date("ymd-Gis", filemtime( get_stylesheet_directory() . $isPath ));

	wp_enqueue_script( 'algolia-client', get_stylesheet_directory_uri() . $clientPath, array(), $clientVersion, true );
	wp_enqueue_script( 'algolia-instant-search', get_stylesheet_directory_uri() . $isPath, array('algolia-client'), $isVersion, true );

	register_mix_script( 'algolia-search-inventory', 'js/src/algolia-search.js', [], true );
	wp_localize_script( 'algolia-search-inventory', 'algoliaTermsHierachy', algolia_terms_hierarchy() );

/*	$args = [
		'algolia_key' => '0L69EIHFYI',
		'algolia_search_token' => '63bfa2a57f089b5f50fabcf21c97d27d',
	];
*/
	$args = [
		'algolia_key' => 'ZFM6L2OKOC',
		'algolia_search_token' => '3b19cb02fbaf645c9c617b556d0a82a0',
	];
	wp_localize_script( 'algolia-search-inventory', 'algolia_credentials', $args );

	wp_enqueue_style( 'algolia-client-style', get_stylesheet_directory_uri() . '/assets/css/main.css', array(), time() );

}


function register_mix_script( $handle, $path, $deps = [], $in_footer = true ) {
	$path = '/' . ltrim( $path );
	$version = mix_version( $path );

	wp_register_script( $handle, get_stylesheet_directory_uri() . '/assets' . $path, $deps, $version, $in_footer );
}
function mix_version( $path ) {
	$manifest = mix_manifest();
	if ( isset( $manifest[ $path ] ) ) {
		$version = str_replace( $path . '?id=', '', $manifest[ $path ] );
	} else {
		$version = STM_THEME_VERSION;
	}

	return $version;
}
function mix_manifest() {
	static $manifest;

	if ( is_null( $manifest ) ) {
		$manifest = json_decode( file_get_contents( get_stylesheet_directory() . '/assets/mix-manifest.json' ), true );
	}

	return $manifest;
}
function get_random_digit(){
	return  rand(1, 1000);
}

function stm_put_log($name, $val = null, $target = 'LOG') {
	$path = get_stylesheet_directory() . "/logs/$name.log";
	if(is_array($val) || is_object($val)){
		$val = var_export($val, true);
	}
	$val = date('d.m.Y H:i:s', time()) . ' - ' . $target . ': ' . $val . "\n";
	file_put_contents($path, $val, FILE_APPEND);
}
function getPostGalleryUrls($gallery, $size)
{
	if (!$gallery) {
		return [];
	}

	$car_photos = [];
	if(!empty($gallery)){
		$gallery = explode(',', $gallery);
		foreach ($gallery as $key => $value) {
			$car_photos[] = wp_get_attachment_image_url($value, $size);
		}
	}

	return $car_photos;
}

//add_filter('woocommerce_get_shop_page_id', function ($url){
//	return 16404;
//});
