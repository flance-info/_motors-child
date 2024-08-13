<?php

function stm_get_categories_terms() {
	$url = "https://www.cruxinterfaces.com/wp-admin/admin-ajax.php?action=stm_category_import_list";
	$ch = curl_init( $url );
	curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt( $ch, CURLOPT_HEADER, 0);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;

}
add_action("wp_ajax_import_categories", 'stm_import_categories_callback');
function stm_import_categories_callback() {

	$message = [];

	$categories = stm_get_categories_terms();
	$categories = json_decode($categories, true);
	foreach ($categories as $post_name => $category) {
		$message[] = "Begin import <strong>'$post_name'</strong>";
		$page = get_page_by_title($post_name, OBJECT, 'product');
		if(!empty($page->ID)){
			$message[] = "<strong>'$post_name'</strong> found";
			foreach ($category as $cat) {
				$term = get_term_by('name', $cat, 'product_cat');
				if(!is_wp_error($term) && !empty($term->term_id)){
					$message[] = "<strong>'$cat' found</strong>";
					$res = wp_set_object_terms($page->ID, $term->term_id, 'product_cat', true);
					if(!is_wp_error($res)){
						$message[] = "Post <strong>'$post_name'</strong> linked with <strong>{$term->name}</strong>";
					}
				}
			}
		}
	}

	wp_send_json(['message' => implode('<br>', $message)]);

}

add_action('template_redirect', function() {
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'import-rrcs'){
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
				function stm_send_rrc_import(offset, limit){
					if(!start) return;
					$.ajax({
						type: 'POST',
						url: 'https://cruxinterfaces.com/wp-admin/admin-ajax.php',
						dataType: 'json',
						data: {
							action: 'import_categories',
							limit: limit,
							offset: offset
						},
						success: function(data) {
							if(data.message){
								$("#message_box").append('<br>'+data.message);
								// offset = offset + limit;
								// g_offset = offset;
								// $("#imported span").html(offset);
								// stm_send_rrc_import(offset, limit);
								// setTimeout(function(){
								// 	$("#message_box").scrollTop($("#message_box")[0].scrollHeight);
								// },100);
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

					stm_send_rrc_import(g_offset, 20);
				});

			})(jQuery);
		</script>
		</html>
		<?php
		die;
	}
});
