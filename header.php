<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>

	<?php
	if ( get_theme_mod( 'logo_font_family', '' ) != "" || get_theme_mod( 'logo_font_size', '' ) != "" || get_theme_mod( 'logo_color', '' ) != "" ) {
		echo "<style>";
		echo ".blogname h1{";
		if ( get_theme_mod( 'logo_font_family', '' ) != "" ) {
			echo "font-family: " . get_theme_mod( 'logo_font_family', '' ) . " !important; ";
		}
		if ( get_theme_mod( 'logo_font_size', '' ) != "" ) {
			echo "font-size: " . get_theme_mod( 'logo_font_size', '' ) . "px !important; ";
		}
		if ( get_theme_mod( 'logo_color', '' ) != "" ) {
			echo "color: " . get_theme_mod( 'logo_color', '' ) . " !important;";
		}
		echo "}";
		echo "</style>";
	}
	?>
</head>

<?php $body_custom_image = get_theme_mod( 'custom_bg_image' ); ?>

<body <?php body_class(); ?> <?php if ( ! empty( $body_custom_image ) ): ?> style="background-image: url('<?php echo esc_url( $body_custom_image ); ?>')" <?php endif; ?> ontouchstart="">
<?php wp_body_open(); ?>
<?php do_action( 'motors_before_header' ); ?>
<div id="wrapper">
	<?php
	if ( ! apply_filters( 'stm_hide_old_headers', false ) ) :
	$header_layout  = stm_get_header_layout();
	$top_bar_layout = '';
	if ( $header_layout == 'boats' || $header_layout == 'car_dealer_two' ) {
		$top_bar_layout = '-boats';
	}
	?>
	<?php
	if ( ! stm_is_auto_parts() ) {

		if ( $header_layout == 'boats' || $header_layout == 'car_dealer_two' ) {
			?>
			<div id="stm-boats-header">
			<?php
		}
		if ( ! is_404() and ! is_page_template( 'coming-soon.php' ) ) {
			if ( $header_layout == 'listing_five' ) {
				get_template_part( 'partials/header/header-classified-five/header' );
			} else {
				get_template_part( 'partials/top', 'bar' . $top_bar_layout );
				?>
				<div id="header">
					<?php get_template_part( 'partials/header/header-' . $header_layout ); ?>
				</div> <!-- id header -->
				<?php
			}
		} elseif ( is_page_template( 'coming-soon.php' ) ) {
			get_template_part( 'partials/header/header-coming', 'soon' );
		} else {
			get_template_part( 'partials/header/header', '404' );
		}
		?>

		<?php
		if ( ( ! is_404() and ! is_page_template( 'coming-soon.php' ) ) && $header_layout == 'boats' || $header_layout == 'car_dealer_two' ) {
			?>
			</div>
			<?php
			get_template_part( 'partials/header/header-boats-mobile' );
		}
		?>
		<?php
	} else {
		if ( ! is_404() and ( is_page_template( 'custom-landing.php' ) ||  is_page_template( 'custom-single.php' ) )) {
				if (is_page_template( 'custom-landing.php' ) ) get_template_part( 'partials/header/header', 'custom' );
				if (is_page_template( 'custom-single.php' ) ) get_template_part( 'partials/header/header', 'customsingle' );
		} else {
			do_action( 'stm_hb', array( 'header' => 'stm_hb_settings' ) );
		}

	} ?>

	<div id="main" <?php if ( stm_is_magazine() ) {
		echo 'style="margin-top: -80px;"';
	} ?>>
		<?php
		else :
			if ( is_404() ) {
				get_template_part( 'partials/header/header', '404' );
			} else {
				do_action( 'stm_motors_header' );
			}
		endif;
		wp_reset_postdata();
		?>
