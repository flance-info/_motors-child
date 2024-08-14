<?php

if ( is_active_sidebar( 'footer' ) ) { ?>
	<?php
	if ( empty( $_wp_sidebars_widgets ) ) :
		$_wp_sidebars_widgets = get_option( 'sidebars_widgets', array() );
	endif;
	$sidebars_widgets_count = $_wp_sidebars_widgets;
	$sidebar_count          = count( $sidebars_widgets_count['footer'] );
	$sidebar_class = '';
	if ( $sidebar_count <= 4 ) {
		$sidebar_class = 'less_4';
	} elseif ( $sidebar_count > 8 ) {
		$sidebar_class = 'more_8';
	}
	$menus = get_registered_nav_menus();
	?>

	<div class="footer">
		<div class="content-d2">
			<div class="logo-d3">
				<div class="image-d4"></div>
			</div>
			<div class="links">
				<?php
				wp_nav_menu( array(
						'theme_location' => 'bottom_menu',
						'container'      => false,
						'items_wrap'     => '%3$s', // Removes the <ul> container
						'link_before'    => '<span class="link-common">',
						'link_after'     => '</span>',
				) );
				?>
			</div>
		</div>
		<div class="credits">
			<div class="divider"></div>
			<div class="row-da">
				<span class="text-db">© 2024 Crux interfacing. All rights reserved.</span>
			</div>
		</div>
	</div>

	<!--
		<div id="footer-main">
			<div class="footer_widgets_wrapper <?php echo esc_attr( $sidebar_class ); ?>">
				<div class="container">
					<div class="widgets cols_<?php echo get_theme_mod( 'footer_sidebar_count', 4 ); ?> clearfix">
						<?php dynamic_sidebar( 'footer' ); ?>
					</div>
				</div>
			</div>
		</div>
!-->
<?php } ?>