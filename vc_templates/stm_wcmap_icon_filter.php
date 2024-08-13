<?php

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_class = (!empty($css)) ? apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' ')) : '';

if(function_exists('stm_wcmap_enqueue_scripts_styles'))
	stm_wcmap_enqueue_scripts_styles('stm_wcmap_icon_filter', 'stm_wcmap_icon_filter');

$base_color = get_theme_mod('site_style_base_color', '#cc6119');
$custom_css = ".stm-wcmap-icon-filter-wrap .stm-wcmap-icon-filter a:hover {
		border-top-color: {$base_color};
	}
	
	.stm-wcmap-icon-filter-wrap .stm-wcmap-title-wrap span:hover {
		color: {$base_color};
		border-bottom: 1px dashed {$base_color};
	}";
wp_add_inline_style( 'stm-wcmap-stm_wcmap_icon_filter', $custom_css );

$type = ($atts['filter_type'] == 'atts') ? 'pa_make' : 'product_cat';
$imgType = ($atts['filter_type'] == 'atts') ? 'stm_attr_wcmap_image' : 'thumbnail_id';

$cats = get_terms(
    array(
        'orderby'       => 'id',
        'order'         => 'ASC',
        'fields'        => 'all',
        'show_count'    => 0,
        'hierarchical'  => 1,
        'hide_empty'    => 0,
        'parent'        => 0,
        'taxonomy'      => $type
    )
);

if($type == 'product_cat' && !is_wp_error($cats)) unset($cats[0]);

$shop_page_id = 16404;//apply_filters( 'woocommerce_get_shop_page_id' , get_option( 'woocommerce_shop_page_id' ) );

?>
<div class="stm-wcmap-icon-filter-wrap" >
    <div class="stm-wcmap-title-wrap">
        <h2><?php echo esc_html($title); ?></h2>
        <span><?php echo esc_html__('See all Makes', 'stm-woocommerce-motors-auto-parts'); ?></span>
    </div>
    <div class="stm-wcmap-icon-filter">
        <?php

        $i = 0;
        if(!is_wp_error($cats)) {
            foreach ( $cats as $k => $cat ) :
                $imgMeta = get_term_meta( $cat->term_id, $imgType, true );
                if ( $imgMeta ) {
                    $img = wp_get_attachment_image_url( $imgMeta, 'full' );
                }

                $link = get_term_link( $cat );

                if ( $type == 'pa_make' ) {
                    $link = get_the_permalink( $shop_page_id ) . '?filter=' . $cat->name;
                }
				if ( $type == 'product_cat' ) {
					if($cat->name == 'Cameras') $link = get_the_permalink( 70683 );
					else $link = get_the_permalink( $shop_page_id ) . '?category=' . $cat->name;
				}

                ?>
                <a href="<?php echo esc_url( $link ); ?>"
                   class="stm_listing_icon_filter_single <?php echo ( $i > 7 ) ? esc_attr( 'non-visible' ) : ''; ?>"
                   title="<?php echo esc_attr( $cat->name ); ?>">
                    <div class="inner">
                        <?php if ( !empty( $img ) ): ?>
                            <div class="image">
                                <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr( $cat->name ); ?>">
                            </div>
                        <?php endif; ?>
                        <div class="name"><?php echo esc_html( $cat->name ); ?><?php if ( $show_count ): ?><span
                                    class="count">(<?php echo esc_html( $cat->count ); ?>)</span><?php endif; ?></div>
                    </div>
                </a>
                <?php

                $i++;
            endforeach;
        }
        ?>
    </div>
</div>
