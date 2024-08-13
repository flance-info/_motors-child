<?php

function stm_get_solutions_terms($offset = 0, $count = 20) {

	//$url = "https://www.cruxinterfacing.com/wp-admin/admin-ajax.php?action=stm_solution_import_list&offset=$offset&limit=$count";
	$url = "https://www.cruxinterfaces.com/wp-admin/admin-ajax.php?action=stm_solution_import_list&offset=$offset&limit=$count";
	$ch = curl_init( $url );
	curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt( $ch, CURLOPT_HEADER, 0);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

function stm_solution_link_products($array_listings, $term) {
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
				$res = wp_set_post_terms( $post_id, $term, 'pa_solution', true );
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

add_action("wp_ajax_import_solutions", 'stm_import_solutions_callback');
function stm_import_solutions_callback() {
	$offset = isset($_REQUEST['offset']) ? (int)$_REQUEST['offset'] : 0;
	$limit = isset($_REQUEST['limit']) ? (int)$_REQUEST['limit'] : 3;
	$message = [];

	$bindings = stm_get_solutions_terms($offset, $limit);
	if($bindings){
		$bindings = json_decode($bindings, true);
		if(count($bindings)) {
			foreach ($bindings as $binding) {
				$term = get_term_by('slug', $binding['slug'], 'pa_solution');
				$message[] = "<strong>Begin import " . $binding['name'] . "</strong>";
				if(!$term){
					$term_obj = wp_insert_term(
						$binding['slug'],
						'pa_solution',
						array(
							'description'=> $binding['description'],
							'slug' => $binding['slug'],
						)
					);
					if(!empty($term_obj) && is_array($term_obj)) {
						$term_id = $term_obj['term_id'];
						$message[] = " - Term '{$binding['slug']}' not found. Added ID: " . $term_id;
					}else{
						$message[] = "<span class='error'> - Error in add term. Var: " . var_export($term_obj, true).'</span>';
					}
				}
				else{
					$term_id = $term['term_id'];
					$message[] = " - Term exist. ID: " . $term_id;
				}
				// Linked product with term
				if(count($binding['listings'])){
					$res = stm_solution_link_products($binding['listings'], $term_id);
					if($res){
						$listings = [];
						foreach ($res as $listing) {
							if(!empty($res['error'])){
								$message[] = "<span class='error'> - Error linked $term_id term to listing: {$listing['title']}: {$listing['id']}</span>";
							}else{
								$message[] = " -- Term '{$binding['name']}' linked to listings {$listing['title']}: {$listing['id']}";
							}
							$message[] = " -- Dump: '" . var_export($listing, true). "'";
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
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'import-solutions'){
		?>
		<html>
			<head>
				<script type='text/javascript' src='https://cruxinterfaces.com/wp-includes/js/jquery/jquery.min.js?ver=3.5.1' id='jquery-core-js'></script>
				<script type='text/javascript' src='https://cruxinterfaces.com/wp-includes/js/jquery/jquery-migrate.min.js?ver=3.3.2' id='jquery-migrate-js'></script>
			</head>
			<body>
				<div class="imported_box">
					<h3>Import Solutions page</h3>
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
							action: 'import_solutions',
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

					stm_send_binding_import(g_offset, 100);
				});

			})(jQuery);
		</script>
		</html>
		<?php
		die;
	}
});
