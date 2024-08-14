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
<div class="section-intro">
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
		<div class="info">
			<div class="stm-content">
				<span class="heading">Interfacing Solutions For Any Vehicle</span><span class="text"
				>CRUX Interfacing Solutions Specializing in automotive OEM
                integration for the digital era, CRUX Interfacing Solutions
                provides an array of products based on proprietary
                research.</span
				>
			</div>
			<div class="actions">
				<div class="button-6">
					<span class="text-sm-7">Learn more</span>
				</div>
				<div class="button-8">
					<span class="text-sm-9">Cruxinterfacing.com</span>
					<div class="icon-btn-right">
						<div class="elements">
							<div class="vector"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>

<style>
	/* Hide mobile menu on desktop */
	.mobile-menu {
		display: none;
	}

	.hamburger-icon {

		display: none;
	}
.desktop-menu .link {

    padding-left: 20px;
}

	/* Responsive Styles */
	@media (max-width: 900px) {
		.desktop-menu {
			display: none;
		}

		/* Hamburger icon default styling */
		.hamburger-icon {
			width: 30px;
			height: 24px;
			display: flex;
			flex-direction: column;
			justify-content: space-between;
			cursor: pointer;
		}

		.hamburger-icon span {
			display: block;
			width: 100%;
			height: 4px;
			background-color: #fff;
			transition: all 0.3s ease;
		}

		/* Transformations to create the "X" shape */
		.hamburger-icon.active span:nth-child(1) {
			transform: rotate(45deg) translate(5px, 5px);
		}

		.hamburger-icon.active span:nth-child(2) {
			opacity: 0; /* Hide the middle line */
		}

		.hamburger-icon.active span:nth-child(3) {
			transform: rotate(-45deg) translate(5px, -5px);
		}

		.hamburger-menu {
			display: block;
		}

		/* Base styles for the mobile menu */
		.mobile-menu {
			display: none; /* Hidden by default */
			flex-direction: column;
			position: absolute;
			top: 50px;
			right: 0;
			background-color: #fff;
			width: 100%;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
			max-height: 0; /* Collapsed by default */
			opacity: 0; /* Transparent by default */
			overflow: hidden; /* Hide overflowing content when collapsed */
			transition: max-height 0.3s ease-out, opacity 0.3s ease-out; /* Transition for sliding and fading */
			z-index: 22222;
		}

		/* Active state - menu slides down and fades in */
		.mobile-menu.active {
			display: flex;
			max-height: 300px; /* Adjust based on content height */
			opacity: 1; /* Fully visible */
		}

		/* Link styling */
		.mobile-menu .link {
			padding: 20px;
			text-align: center;
			border-bottom: 1px solid #ddd;
		}

		.mobile-menu .link a {
			text-decoration: none;
			color: #333;
		}


	}


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

