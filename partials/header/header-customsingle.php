<?php

$menu_name  = 'primary'; // Replace with your menu slug or ID
$locations  = get_nav_menu_locations(); // Get all menu locations
$menu_id    = $locations[ $menu_name ]; // Get the menu ID based on the location
$menu_items = wp_get_nav_menu_items( $menu_id ); // Retrieve the menu items
?>
<?php if ( $menu_items ): ?>

	<div class="mobile-menu">
		<?php foreach ( $menu_items as $item ): ?>
			<?php if ( $item->menu_item_parent == 0 && $item->post_title != 'dgwt_wcas_search_box' ): // It's a top-level item ?>
				<span class="link">
                            <a href="<?php echo esc_url( $item->url ); ?>">
                                <?php echo esc_html( $item->post_title ); ?>
                            </a>
                        </span>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
<div class="section-intro single-page-banner">
	<div class="stm-header">
		<div class="stm-container">
			<div class="stm-logo">
				<div class="stm-image"></div>
			</div>
			<div class="column">
				<div class="column-1">
					<div class="desktop-menu">
						<?php foreach ( $menu_items as $item ): ?>
							<?php if ( $item->menu_item_parent == 0 && $item->post_title != 'dgwt_wcas_search_box' ): // It's a top-level item ?>
								<span class="link">
                            <a href="<?php echo esc_url( $item->url ); ?>">
                                <?php echo esc_html( $item->post_title ); ?>
                            </a>
                        </span>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="column-5">
					<div class="stm-button">
						<span class="text-sm">Visit store</span>
					</div>
				</div>
				<div class="hamburger-menu">
					<span class="hamburger-icon">
						<!-- Hamburger icon (three lines) -->
						<span></span>
						<span></span>
						<span></span>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="flex-row-ba">
		<div class="stm-content">
			<h1>
				<?php echo get_the_title(); ?>
			</h1>
		</div>
	</div>

</div>

<style>


</style>

<script>
	document.addEventListener('DOMContentLoaded', function () {
		const hamburgerMenu = document.querySelector('.hamburger-menu');
		const mobileMenu = document.querySelector('.mobile-menu');
		const hamburgerIcon = document.querySelector('.hamburger-icon');

		hamburgerMenu.addEventListener('click', function () {
			hamburgerIcon.classList.toggle('active');
			mobileMenu.classList.toggle('active');
		});
	});


</script>

