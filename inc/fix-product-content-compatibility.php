<?php

function stm_fix_product_content_co($offset = 0, $count = 20) {
	$args = [
		'post_type' => 'product',
	  	'offset' => $offset,
	  	'posts_per_page' => $count,
	  	'post_status' => 'any'
	];
	$products = get_posts($args);
	$result = [];
	foreach ($products as $product) {
		$result[] = [
			'content' => $product->post_content,
		  	'id' => $product->ID,
		  	'title' => $product->post_title
		];
	}
	return $result;
}

function stm_fix_product_content_compatibility($content) {
	$updated = false;
	preg_match_all('/(<span style="color: black;">[0-9]+[^0-9]+[0-9]+[^<]+<\/span>)([^\n]+)/m', $content, $matches);
	if(!empty($matches[2])){
		foreach ($matches[2] as $key => $item) {
			if(empty($item) || strlen($item) == 1) continue;
			if(stripos($item, '<strong>') == false) continue;
			$content = str_replace($item, "<br>$item", $content);
			$updated = true;
		}
	}
	return [
		'updated' => $updated,
	  	'content' => $content
	];
}

add_action("wp_ajax_fix_product_content_co", 'stm_fix_product_content_co_callback');
function stm_fix_product_content_co_callback() {
	$offset = isset($_REQUEST['offset']) ? (int)$_REQUEST['offset'] : 0;
	$limit = isset($_REQUEST['limit']) ? (int)$_REQUEST['limit'] : 3;
	$message = [];

	$products = stm_fix_product_content_co($offset, $limit);
	foreach ($products as $product) {
		update_post_meta($product['id'], 'stm_backup_content', $product['content']);
		$message[] = " - Start product '{$product['title']}' content update...";
		$res = stm_fix_product_content_compatibility($product['content']);

		if($res['updated']){
			$post = wp_update_post([
				'ID' => $product['id'],
				'post_content' => $res['content']
			]);
			if(!is_wp_error($post)) $message[] = " - - Product '{$product['title']}' content updated. ID: " . $product['id'];
			else $message[] = "<span class='error'> - - Error on update post {$product['id']}. Var: " . var_export($post, true).'</span>';
		}
	}
	wp_send_json(['limit' => count($products), 'message' => implode('<br>', $message)]);
}

add_action('template_redirect', function() {
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'fix-product-content-compatibility'){
		?>
		<html>
			<head>
				<script type='text/javascript' src='https://cruxinterfacing.com/wp-includes/js/jquery/jquery.min.js?ver=3.5.1' id='jquery-core-js'></script>
				<script type='text/javascript' src='https://cruxinterfacing.com/wp-includes/js/jquery/jquery-migrate.min.js?ver=3.3.2' id='jquery-migrate-js'></script>
			</head>
			<body>
				<div class="imported_box">
					<h3>Fix Product Content Compatibility</h3>
					<strong id="imported" style="display: block;margin: 30px 0">Fixed: <span>0</span></strong>
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
					width: fit-content;
					min-width: 768px;
					border: 1px solid #dadada;
					margin: 30px 0;
					padding: 15px;
					background-color: #eaeaea;
					box-shadow: 0 0 6px rgba(0,0,0,0.5) inset;
					font-size: 12px;
				}
				.error{
					color: red;
				}
			</style>
		<script>
			(function($){
				let start = false;
				let g_offset = <?php echo ( !empty($_REQUEST['offset']) ? (int)$_REQUEST['offset'] : 0 ) ?>;
				function stm_send_2234122(offset, limit){
					if(!start) return;
					$.ajax({
						type: 'POST',
						url: 'https://cruxinterfacing.com/wp-admin/admin-ajax.php',
						dataType: 'json',
						data: {
							action: 'fix_product_content_co',
							limit: limit,
							offset: offset
						},
						success: function(data) {
							if(data.message){
								$("#message_box").append('<br>'+data.message);
								offset = offset + limit;
								g_offset = offset;
								$("#imported span").html(offset + data.limit - 10);
								stm_send_2234122(offset, limit);
								setTimeout(function(){
									$("#message_box").scrollTop($("#message_box")[0].scrollHeight);
								},100);
							}else{
								$('#start_import').text("Start");
								start = false;
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

					stm_send_2234122(g_offset, 10);
				});

			})(jQuery);
		</script>
		</html>
		<?php
		die;
	}
});


//add_filter('the_content', function($content){
//	$_content = get_post_meta(get_the_ID(), 'stm_test_content', true);
//	if(!empty($_content)) $content = $_content;
//	return $content;
//});