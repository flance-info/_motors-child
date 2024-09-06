<?php

if ( is_active_sidebar( 'footer' ) ) { ?>
	<?php
	if ( empty( $_wp_sidebars_widgets ) ) :
		$_wp_sidebars_widgets = get_option( 'sidebars_widgets', array() );
	endif;
	$sidebars_widgets_count = $_wp_sidebars_widgets;
	$sidebar_count          = count( $sidebars_widgets_count['footer'] );
	$sidebar_class          = '';
	if ( $sidebar_count <= 4 ) {
		$sidebar_class = 'less_4';
	} elseif ( $sidebar_count > 8 ) {
		$sidebar_class = 'more_8';
	}
	$menus = get_registered_nav_menus();
	?>
	<div class="frame-c6">
		<div class="row">
			<div class="col-12 col-md-2 d-flex align-items-center">
				<div class="follow-us">Follow us</div>
			</div>
			<div class="col-12 col-md-10">
				<div class="row justify-content-md-start justify-content-center">
					<div class="col-xss-12  col-xs-6 col-sm-6 col-md-3 mb-2">
						<a href="https://www.facebook.com/CRUXINTERFACING" class="frame-c7">

							<div class="icon-c8"></div>
							<span class="social-media">We’re on facebook</span>

						</a>
					</div>
					<div class="col-xss-12 col-xs-6 col-sm-6 col-md-3 mb-2">
						<a href="https://www.youtube.com/@cruxinterfaces" class="frame-c7">
							<div class="icon-ca"></div>
							<span class="social-media-cb">We’re on Youtube</span>
					</a>
					</div>
					<div class="col-xss-12 col-xs-6 col-sm-6 col-md-3 mb-2">
							<a href="https://www.instagram.com/cruxinterfacingsolutions/?hl=en" class="frame-c7">
							<div class="logo-apps"></div>
							<span class="social-media-cd">We’re on instagram</span>
						</a>
					</div>
					<div class="col-xss-12 col-xs-6 col-sm-6 col-md-3 mb-2">
							<a href="https://www.linkedin.com" class="frame-c7">
							<div class="logo-apps-cf">
								<div class="vector-d0"></div>
							</div>
							<span class="social-media-d1">We’re on linkedin</span>
						</a>
					</div>
				</div>
			</div>

		</div>
	</div>
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