<?php

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$atts = array(
		'limit'        => $number_posts,
		'columns'      => $columns,
		'orderby'      => isset( $orderby ) ? $orderby : 'date',  // Default to 'date' if not set
		'order'        => isset( $order ) ? $order : 'DESC',      // Default to 'DESC' if not set
		'cat_operator' => 'IN',
		'best_selling' => isset( $best_selling ) && $best_selling === 'best_selling',
		'product_ids'  => ! empty( $products_ids ) ? explode( ', ', $products_ids ) : array(), // Convert string to array
);
$args = array(
		'limit'   => $atts['limit'],
		'orderby' => $atts['orderby'],
		'order'   => $atts['order'],
		'return'  => 'objects',             // Return full product objects (can be 'ids' if you only need the IDs)
);
if ( ! empty( $atts['category'] ) ) {
	$args['category'] = array( $atts['category'] );
}
if ( ! empty( $atts['product_ids'] ) ) {
	$args['post__in'] = $atts['product_ids']; // Filter by specific product IDs
}
if ( $atts['best_selling'] ) {
	$args['meta_key'] = 'total_sales';
	$args['orderby']  = 'meta_value_num';
}

$query    = new WC_Product_Query( $args );
$products = $query->get_products();
?>

<div class="frame-12">
	<div class="section-header-13">
		<span class="new-products">New products</span>
	</div>
	<div class="container-14 row">
		<?php foreach ( $products as $product ): ?>
		<div class="col-xss-12 col-xsm-12 col-xs-6 col-sm-6 col-md-4 col-lg-3">
			<div class="item-course-main-dark">
				<div class="frame-15">
					<div class="image-16">
						<?php echo $product->get_image(); // Display product image ?>
					</div>
				</div>
				<div class="frame-17">
					<div class="frame-18">
                        <span class="swrgm-radio-replacement">
                            <?php echo $product->get_name(); // Display product name ?>
                        </span>
					</div>
					<div class="frame-19">
						<div class="frame-1a">
                            <span class="price">
                                <?php echo ($product->get_price()) ? wc_price( $product->get_price() ) : ''; // Display product price ?>
                            </span>
						</div>
						<div class="button-1b">
							<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="text-sm-1c">
								Shop now
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>

	<div class="button-6a">
		<a href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>" class="text-sm-6b">View more products</a>
	</div>
</div>
