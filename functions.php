<?php
require_once 'device/main.php';
include_once "inc/custom-binding.php";
//include_once "inc/import-binding.php";
//include_once "inc/import-rrc.php";
//include_once "inc/import-solutions.php";
//include_once "inc/import-products.php";
include_once "inc/woocommerce-algolia.php";
include_once "inc/widgets/product-category-custom.php";
include_once "inc/vc_elements.php";
include_once "inc/new_product_metabox.php";
include_once "inc/tablepress-render.php";
include_once "inc/product-group-icons.php";
include_once "inc/fix-product-content-compatibility.php";

add_action( 'wp_enqueue_scripts', 'stm_enqueue_parent_styles',1000 );
function stm_enqueue_parent_styles() {
	wp_deregister_script('stm-wcmap-stm_wcmap_parts_search-js');
	wp_enqueue_script(
		'jquery-initialize-js',
		get_stylesheet_directory_uri().'/assets/js/jquery.initialize.min.js',
		array('jquery'), time(), true
	);
	wp_enqueue_script(
		'stm-wcmap-stm_wcmap_parts_search',
		get_stylesheet_directory_uri().'/assets/js/stm_wcmap_parts_search.js',
		array('jquery', 'jquery-initialize-js'), time(), true
	);

	wp_enqueue_script( 'js-owl', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.js', null, STM_THEME_VERSION, 'all' );
	wp_enqueue_style( 'css-owl', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css', null, STM_THEME_VERSION, 'all' );

	wp_enqueue_style( 'stm-additional-style', get_stylesheet_directory_uri().'/assets/css/additional.css', ['stm-theme-style'], '1.2.2', 'all' );
	wp_enqueue_style( 'stm-theme-style-child', get_stylesheet_directory_uri().'/style.css', null, '1.2.3', 'all' );

}

function custom_upload_mimes($mime_types)
{
    $mime_types['bin'] = 'application/octet-stream';
    return $mime_types;
}
add_filter('upload_mimes', 'custom_upload_mimes');

function hook_css() {
    ?>
        <script>
			jQuery( document ).ready(function() {
           jQuery('.month input').bind('keyup','keydown', function(event) {
				var inputLength = event.target.value.length;
				if(inputLength === 2){
				  var thisVal = event.target.value;
				  thisVal += '/';
				  jQuery(event.target).val(thisVal);
				}
			  });
			});
        </script>
    <?php
}
add_action('wp_head', 'hook_css');

function wp_11326339_custom_description() {
    global $product;

    $wc_product = wc_get_product( $product );

    if ( ! $wc_product ) {
        return false;
    }

$short_description = $wc_product->get_short_description();

    if ( '' !== $short_description ) {
        echo '<div itemprop="description">' . do_shortcode( wpautop( wptexturize( $short_description ) ) ) . '</div>';
    }
}

/**
	* Remove Additional Information Tab @ WooCommerce Single Product Page
*/
add_action( 'woocommerce_before_single_product', 'njengah_remove_product_tabs', 10 );
function njengah_remove_product_tabs( ) {
	global $product;

	// If the WC_product Object is not defined globally
	if ( ! is_a( $product, 'WC_Product' ) ) {
	  $product = wc_get_product( get_the_id() );
	}
	$new_product = get_post_meta( get_the_id(), 'new_product', 1 );
	?>

	<h1 class="product_title entry-title"><?php echo $product->get_name(); ?></h1>
	<?php if ($new_product === 'yes'): ?>
	  <div class="product_new">New</div>
	<?php endif;
}

// Remove the product description Title
add_filter( 'woocommerce_product_description_heading', '__return_null' );


// Change the product description title
add_filter('woocommerce_product_description_heading', 'change_product_description_heading');
function change_product_description_heading() {
 return __('', 'woocommerce');
}


//add_action('wp_head', 'replace_custom_interfaces_url');
function replace_custom_interfaces_url(){
    ?>
    <script>
        jQuery(document).ready(function(){

            setInterval(function(){
                jQuery( '#listings-result ol li' ).each( function(){

                     jQuery(this).find( 'a img' ).attr('src', jQuery(this).find( 'a img' ).attr('src').replace('interfacing', 'interfaces'));

                } );

                jQuery( '#listings-result ol li' ).each( function(){
                     jQuery(this).find( 'a' ).attr('href', jQuery(this).find( 'a' ).attr('href').replace('interfacing', 'interfaces'));
                } );
                //this code runs every second
            }, 1000);

            // jQuery( document ).ajaxComplete(function() {

            //      setTimeout(function(){

            //         jQuery( '#listings-result ol li' ).each( function(){

            //              jQuery(this).find( 'a img' ).attr('src', jQuery(this).find( 'a img' ).attr('src').replace('interfacing', 'interfaces'));

            //         } );
            //     },3000);

            // });


            // setTimeout(function(){

            //     jQuery( '#listings-result ol li' ).each( function(){

            //          jQuery(this).find( 'a img' ).attr('src', jQuery(this).find( 'a img' ).attr('src').replace('interfacing', 'interfaces'));

            //     } );
            // },3000);


        });
    </script>
    <?php
}

add_filter( 'wp_mail_smtp_custom_options', function( $phpmailer ) {
    $phpmailer->AuthType = 'LOGIN';
    return $phpmailer;
} );
