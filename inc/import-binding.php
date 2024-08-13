<?php

function stm_get_binding_terms($offset = 0, $count = 20) {

	$url = "https://www.cruxinterfaces.com/wp-admin/admin-ajax.php?action=stm_binding_import_list&offset=$offset&limit=$count";
	$ch = curl_init( $url );
	curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt( $ch, CURLOPT_HEADER, 0);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}


function stm_create_binding_terms_slug($make, $model, $year) {
	$name = [];
	if(!empty($make)) $name[] = $make;
	if(!empty($model)) $name[] = $model;
	if(!empty($year)) {
		if(is_array($year))
			$name[] = implode('-', $year);
		else
			$name[] = $year;
	}
	return implode('-', $name);
}
function stm_check_available_binding($mmy = []) {
	$slug = stm_create_binding_terms_slug($mmy[0], $mmy[1], $mmy[2]);
	$binding = get_term_by('slug', $slug,'custom-binding', ARRAY_A);
	if($binding && !is_wp_error($binding)) return $binding;
	return false;
}

function stm_binding_link_products($array_listings, $term) {
	global $wpdb;
	if($array_listings && count($array_listings)) {
		$resource = [];
		foreach ($array_listings as $array_listing) {
			$sql = $wpdb->prepare(
				"SELECT ID FROM $wpdb->posts WHERE post_title=%s && post_type='product'",
				$array_listing['title']
			);
			$post_id = $wpdb->get_var( $sql );
			if($post_id){
				$res = wp_set_post_terms( $post_id, $term, 'custom-binding', true );
				if(!$res || is_wp_error($res)) $resource[] = [
					'error' => true,
					'id' => $post_id,
					'res' => $res,
					'term' => $term,
					'sql' => $sql,
					'title' => $array_listing['title']
				];
				if(is_array($res)) $resource[] = [
					'id' => $post_id,
					'res' => $res,
					'sql' => $sql,
					'title' => $array_listing['title'],
					'term' => $term
				];
			}else{
				$resource[] = [
					'error' => true,
					'id' => $post_id
				];
			}
		}
		return $resource;
	}
	return false;
}


function stm_custom_binding_mmy_exists($arr) {
	$error = [];
	foreach ($arr as $tax => $terms) {
		$pa_tax = 'pa_'.$tax;
		if(is_array($terms)){
			foreach ($terms as $term) {
				if(!term_exists($term, $pa_tax)){
					$term_obj = wp_insert_term(
						$term,
						$pa_tax,
						array(
							'description'=> '',
							'slug' => $term,
						)
					);
					if(!$term_obj  || is_wp_error($term_obj)) $error[] = $term_obj;
				}
			}
		}else{
			if(!term_exists($terms, $pa_tax) && !empty($terms)){
				$term_obj = wp_insert_term(
					$terms,
					$pa_tax,
					array(
						'description'=> '',
						'slug' => $terms,
					)
				);
				if(!$term_obj  || is_wp_error($term_obj)) $error[] = $term_obj;
			}
		}
	}
	if($error) return $error;
	else return true;
}

add_action('template_redirect', function() {
	if (isset($_REQUEST['bela'])) {
		$offset = 0;
		$limit = 3;

		$bindings = stm_get_binding_terms($offset, $limit);
		if ($bindings) {
			$bindings = json_decode($bindings, true);
			if (count($bindings)) {
				foreach ($bindings as $binding) {
					$term = stm_check_available_binding([
						$binding['make'], $binding['models'], $binding['year']
					]);
					if (!$term) {
						$slug = stm_create_binding_terms_slug($binding['make'], $binding['models'],
							$binding['year']);
						$term_obj = wp_insert_term(
							$slug,
							'custom-binding',
							[
								'description' => $binding['description'],
								'slug' => $slug,
							]
						);
						if (!empty($term_obj) && is_array($term_obj)) {
							$term_id = $term_obj['term_id'];

							if (!empty($term_id)) {
								$terms_ids[] = $term_id;
								update_term_meta($term_id, 'make', $binding['make']);
								update_term_meta($term_id, 'models', $binding['models']);

								delete_term_meta($term_id, 'year');

								foreach ($binding['year'] as $item) {
									add_term_meta($term_id, 'year', $item);
								}

								$term = stm_custom_binding_mmy_exists([
									'make' => $binding['make'],
									'model' => $binding['models'],
									'part-year' => $binding['year']
								]);

								var_dump($term);
							}
						}
					}
					else {
						$term_id = $term['term_id'];
						update_term_meta($term_id, 'make', $binding['make']);
						update_term_meta($term_id, 'models', $binding['models']);

						delete_term_meta($term_id, 'year');
						foreach ($binding['year'] as $item) {
							add_term_meta($term_id, 'year', $item);
						}

						$term = stm_custom_binding_mmy_exists([
							'make' => $binding['make'],
							'model' => $binding['models'],
							'part-year' => $binding['year']
						]);

						var_dump($term);

					}
					// Linked product with term
					if (count($binding['listings'])) {
						$res = stm_binding_link_products($binding['listings'], $term_id);

					}
					else {

					}
				}
			}
		}
	}
});

add_action("wp_ajax_import_bindings", 'stm_import_bindings_callback');
function stm_import_bindings_callback() {
	$offset = isset($_REQUEST['offset']) ? (int)$_REQUEST['offset'] : 0;
	$limit = isset($_REQUEST['limit']) ? (int)$_REQUEST['limit'] : 3;
	$message = [];

	$bindings = stm_get_binding_terms($offset, $limit);
	if($bindings){
		$bindings = json_decode($bindings, true);
		if(count($bindings)) {
			foreach ($bindings as $binding) {
				$term = stm_check_available_binding([$binding['make'], $binding['models'], $binding['year']]);
				$message[] = "<strong>Begin import " .
					stm_create_binding_terms_slug($binding['make'], $binding['models'], $binding['year']).
					"</strong>";
				if(!$term){
					$slug = stm_create_binding_terms_slug($binding['make'], $binding['models'], $binding['year']);
					$term_obj = wp_insert_term(
						$slug,
						'custom-binding',
						array(
							'description'=> $binding['description'],
							'slug' => $slug,
						)
					);
					if(!empty($term_obj) && is_array($term_obj)) {
						$term_id = $term_obj['term_id'];
						$message[] = " - Term '$slug' not found. Added ID: " . $term_id;

						if(!empty($term_id)) {
							$terms_ids[] = $term_id;
							update_term_meta( $term_id, 'make', $binding['make'] );
							update_term_meta( $term_id, 'models', $binding['models'] );

							delete_term_meta($term_id, 'year');
							foreach ($binding['year'] as $item) {
								add_term_meta( $term_id, 'year', $item );
							}

							$pa_term = stm_custom_binding_mmy_exists([
								'make' => $binding['make'],
								'model' => $binding['models'],
								'part-year' => $binding['year']
							]);
							if(is_array($pa_term)){
								$message[] = "<span class='error'> - Error in add pa-term. Var: " . var_export($pa_term, true).'</span>';
							}
						}
					}else{
						$message[] = "<span class='error'> - Error in add term. Var: " . var_export($term_obj, true).'</span>';
					}
				}
				else{

					$term_id = $term['term_id'];
					$message[] = " - Term exist. ID: " . $term_id;

					update_term_meta( $term_id, 'make', $binding['make'] );
					update_term_meta( $term_id, 'models', $binding['models'] );

					delete_term_meta($term_id, 'year');
					foreach ($binding['year'] as $item) {
						add_term_meta( $term_id, 'year', $item );
					}

					$pa_term = stm_custom_binding_mmy_exists([
						'make' => $binding['make'],
						'model' => $binding['models'],
						'part-year' => $binding['year']
					]);
					if(is_array($pa_term)){
						$message[] = "<span class='error'> - Error in add pa-term. Var: " . var_export($pa_term, true).'</span>';
					}


				}
				// Linked product with term
				if(count($binding['listings'])){
					$res = stm_binding_link_products($binding['listings'], $term_id);
					if($res){
						$listings = [];
						foreach ($res as $listing) {
							if(!empty($res['error'])){
								$message[] = "<span class='error'> - Error linked $term_id term to listing: {$listing['title']}: {$listing['id']}</span>";
							}else{
								$message[] = " -- Term $term_id linked to listings {$listing['title']}: {$listing['id']}";
							}
//							$message[] = " -- Term $term_id linked to listings '" . $listing['title']. "'";
							if(isset($listing['error'])){
								$message[] = "<span class='error'> -- Dump: '" . var_export($listing, true). "'</span>";
							}else{
								$message[] = " -- Dump: '" . var_export($listing, true). "'";
							}

						}
					}else{
						$message[] = "<span class='error'> - Error linked $term_id term to listing: " . var_export($binding['listings'], true).'</span>';
						$message[] = "<span class='error'> - Error linked: " . var_export($res, true).'</span>';
					}
				}else{
					$message[] = " - Not listings to linked term";
				}

			}
		}
	}
	wp_send_json(['message' => implode('<br>', $message)]);

}

add_action('template_redirect', function() {
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'import-bindings'){
		?>
		<html>
			<head>
				<script type='text/javascript' src='https://cruxinterfaces.com/wp-includes/js/jquery/jquery.min.js?ver=3.5.1' id='jquery-core-js'></script>
				<script type='text/javascript' src='https://cruxinterfaces.com/wp-includes/js/jquery/jquery-migrate.min.js?ver=3.3.2' id='jquery-migrate-js'></script>
			</head>
			<body>
				<div class="imported_box">
					<h3>Import Bindigs page</h3>
					<strong id="imported" style="display: block;margin: 30px 0">Imported: <span>0</span></strong>
					<button id="start_import">Begin</button>
					<div id="message_box"></div>
				</div>
			</body>
			<style>
				.imported_box{
					padding: 0 30px;
				}
				#message_box{
					height: 400px;
					overflow: auto;
					width: 768px;
					border: 1px solid #dadada;
					margin: 30px 0;
					padding: 15px;
					background-color: #eaeaea;
					box-shadow: 0 0 6px rgba(0,0,0,0.5) inset;
				}
				.error{
					color: red;
				}
			</style>
		<script>
			(function($){
				let start = false;
				let g_offset = <?php echo ( !empty($_REQUEST['offset']) ? (int)$_REQUEST['offset'] : 0 ) ?>;
				function stm_send_binding_import(offset, limit){
					if(!start) return;
					$.ajax({
						type: 'POST',
						url: 'https://cruxinterfaces.com/wp-admin/admin-ajax.php',
						dataType: 'json',
						data: {
							action: 'import_bindings',
							limit: limit,
							offset: offset
						},
						success: function(data) {
							if(data.message){
								$("#message_box").append('<br>'+data.message);
								offset = offset + limit;
								g_offset = offset;
								$("#imported span").html(offset);
								stm_send_binding_import(offset, limit);
								setTimeout(function(){
									$("#message_box").scrollTop($("#message_box")[0].scrollHeight);
								},100);
							}
						},
						error:function (xhr, ajaxOptions, thrownError){
							console.log('error...', xhr);
							//error logging
						},
						complete: function(){
							//afer ajax call is completed
						}
					});
				}



				$('#start_import').on("click", function(){
					if(start){
						start = false;
						$(this).text("Start");
					}else{
						start = true;
						$(this).text("Stop");
					}

					stm_send_binding_import(g_offset, 20);
				});

			})(jQuery);
		</script>
		</html>
		<?php
		die;
	}
});
