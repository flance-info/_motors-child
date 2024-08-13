<?php

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
// Set your parameters
$atts = array(
		'number'     => $number,
		'orderby'    => isset( $orderby ) ? $orderby : 'date',
		'order'      => isset( $order ) ? $order : 'DESC',
		'columns'    => $columns,
		'hide_empty' => $hide_empty,
		'show_count' => $show_count,
);
// Prepare the query arguments for WooCommerce categories
$args               = array(
		'number'     => $atts['number'],
		'orderby'    => $atts['orderby'],
		'order'      => $atts['order'],
		'hide_empty' => ( $atts['hide_empty'] === 'yes' ) ? 1 : 0,
);
$product_categories = get_terms( 'product_cat', $args );
if ( ! empty( $product_categories ) ) {
	?>
	<div class="frame">
		<div class="section-header">
			<span class="use-cases">Use cases</span>
		</div>

		<div class="container-14 row">
			<?php foreach ( $product_categories as $category ):
				$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
				$image = wp_get_attachment_url( $thumbnail_id );
				?>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="frame-a">
						<div class="image-b"
								<?php if ( $image ): ?>
									style="background:url(<?php echo esc_url( $image ); ?>) no-repeat center; background-size: cover;"
								<?php endif; ?>
						>
						</div>
						<div class="rectangle"></div>
						<span class="safety-cameras"><?php echo esc_html( $category->name ); ?></span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
} else {
	echo '<p>No categories found.</p>';
}
?>
