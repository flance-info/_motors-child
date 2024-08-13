<?php
function stm_get_products($offset = 0, $count = 20) {
	$url = "https://www.cruxinterfaces.com/wp-admin/admin-ajax.php?action=stm_import_listings&offset=$offset&limit=$count";
	$ch = curl_init( $url );
	curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt( $ch, CURLOPT_HEADER, 0);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

/**
 * Insert an attachment.
 *
 * @param  string  $url Url of image
 * @return int|WP_Error|array The attachment ID on success. The value 0 or WP_Error on failure.
 */
function stm_upload_import_image($url) {
	// Gives us access to the download_url() and wp_handle_sideload() functions
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	$timeout_seconds = 5;
	$url = strtok($url, '?');
	// Download file to temp dir
	$temp_file = download_url( $url, $timeout_seconds );
	if ( !is_wp_error( $temp_file ) ) {
		// Array based on $_FILE as seen in PHP file uploads
		$file = array(
			'name'     => basename($url), // ex: wp-header-logo.png
			'type'     => mime_content_type($temp_file),
			'tmp_name' => $temp_file,
			'error'    => 0,
			'size'     => filesize($temp_file),
		);
		$overrides = array(
			// Tells WordPress to not look for the POST form
			// fields that would normally be present as
			// we downloaded the file from a remote server, so there
			// will be no form fields
			// Default is true
			'test_form' => false,
			// Setting this to false lets WordPress allow empty files, not recommended
			// Default is true
			'test_size' => true,
		);
		// Move the temporary file into the uploads directory
		$results = wp_handle_sideload( $file, $overrides );

		if ( !empty( $results['error'] ) ) {
			// Insert any error handling here
			return $results;
		} else {
			$filename  = $results['file']; // Full path to the file
			$local_url = $results['url'];  // URL to the file in the uploads dir
			$type      = $results['type']; // MIME type of the file
			// Perform any actions here based in the above results
			$wp_filetype = wp_check_filetype(basename($filename), null );

			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => $filename,
				'post_content' => '',
				'post_status' => 'inherit'
			);
			return wp_insert_attachment( $attachment, $filename );
		}
	}else{
		return $temp_file;
	}
}

add_action("wp_ajax_import_listings", 'stm_import_listings_callback');
function stm_import_listings_callback() {
	$offset = isset($_REQUEST['offset']) ? (int)$_REQUEST['offset'] : 0;
	$limit = isset($_REQUEST['limit']) ? (int)$_REQUEST['limit'] : 10;
	$message = [];
	global $wpdb;

	$listings = stm_get_products($offset, $limit);
	if($listings){
		$listings = json_decode($listings, true);
		if(count($listings)) {
			foreach ($listings as $listing) {
				$sql = $wpdb->prepare(
					"SELECT ID FROM $wpdb->posts WHERE post_title=%s && post_type='product'",
					$listing['listing']['post_title']
				);
				$post_id = $wpdb->get_var( $sql );
				if($post_id){
					$message[] = "<span class='info'>Listing \"{$listing['listing']['post_title']}\" already exists!</span>";
				}else{
					$message[] = "<span class='warning'>Listing \"{$listing['listing']['post_title']}\" not found!</span>";
					// Add new listing
					if(!empty($listing['attachment'])){
						// Add product
						$args = $listing['listing'];
						unset($args['ID']);
						$args['post_type'] = 'product';
						$post_id = wp_insert_post($args);
						if(!is_wp_error($post_id)){
							$message[] = "<span class='success'>Listing \"{$listing['listing']['post_title']}\" added!</span>";
							// Set attachment
							$thumbnail_id = stm_upload_import_image($listing['attachment']);
							if(is_integer($thumbnail_id)){
								$res = set_post_thumbnail($post_id, $thumbnail_id);
								if($res){
									$message[] = "<span class='success'>Listing \"{$listing['listing']['post_title']}\" image linked!</span>";
								}else{
									$message[] = "<span class='error'>Listing \"{$listing['listing']['post_title']}\" image cant linked!</span>";
								}
							}else{
								$message[] = "<span class='error'>Listing \"{$listing['listing']['post_title']}\" image cant upload!</span>";
							}
						}else{
							$message[] = "<span class='error'>Listing \"{$listing['listing']['post_title']}\" cant added!</span>";
						}
					}else{
						$message[] = "<span class='warning'>Listing \"{$listing['listing']['post_title']}\" image not found!</span>";
					}
				}
			}
		}
	}
	wp_send_json(['message' => implode('<br>', $message)]);
}

add_action('template_redirect', function() {
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'import-listings'){
		?>
		<html>
			<head>
				<script type='text/javascript' src='https://cruxinterfaces.com/wp-includes/js/jquery/jquery.min.js?ver=3.5.1' id='jquery-core-js'></script>
				<script type='text/javascript' src='https://cruxinterfaces.com/wp-includes/js/jquery/jquery-migrate.min.js?ver=3.3.2' id='jquery-migrate-js'></script>
			</head>
			<body>
				<div class="imported_box">
					<h3>Import Listings page</h3>
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
					max-width: 1200px;
					border: 1px solid #dadada;
					margin: 30px 0;
					padding: 15px;
					background-color: #eaeaea;
					box-shadow: 0 0 6px rgba(0,0,0,0.5) inset;
				}
				.error{
					color: red;
				}
				.success{
					color: #16b910;
				}
				.warning{
					color: #bdbd00;
				}
				.info{
					color: #009898;
				}
			</style>
		<script>
			(function($){
				let start = false;
				let g_offset = <?php echo ( !empty($_REQUEST['offset']) ? (int)$_REQUEST['offset'] : 0 ) ?>;
				function stm_send_import_listings(offset, limit){
					if(!start) return;
					$.ajax({
						type: 'POST',
						url: 'https://cruxinterfaces.com/wp-admin/admin-ajax.php',
						dataType: 'json',
						data: {
							action: 'import_listings',
							limit: limit,
							offset: offset
						},
						success: function(data) {
							if(data.message){
								$("#message_box").append('<br>'+data.message);
								offset = offset + limit;
								g_offset = offset;
								$("#imported span").html(offset);
                              	stm_send_import_listings(offset, limit);
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

                  stm_send_import_listings(g_offset, 20);
				});

			})(jQuery);
		</script>
		</html>
		<?php
		die;
	}
});
