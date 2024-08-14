<?php

$menu_name  = 'primary'; // Replace with your menu slug or ID
$locations  = get_nav_menu_locations(); // Get all menu locations
$menu_id    = $locations[ $menu_name ]; // Get the menu ID based on the location
$menu_items = wp_get_nav_menu_items( $menu_id ); // Retrieve the menu items
?>
<div class="section-intro">
	<div class="stm-header">
		<div class="stm-container">
			<div class="stm-logo">
				<div class="stm-image"></div>
			</div>
			<div class="column">
				<div class="column-1">
					<div class="column-1">
						<?php if ( $menu_items ): ?>
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
					</div>


					<div class="column-5">
						<div class="stm-button">
							<span class="text-sm">Visit store</span>
						</div>
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

	style

