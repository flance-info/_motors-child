<?php

$menu_name  = 'primary'; // Replace with your menu slug or ID
$locations  = get_nav_menu_locations(); // Get all menu locations
$menu_id    = $locations[ $menu_name ]; // Get the menu ID based on the location
$menu_items = wp_get_nav_menu_items( $menu_id ); // Retrieve the menu items
wp_nav_menu( array(
    'theme_location' => 'primary',
    'container'      => false,
    'items_wrap'     => '<div class="menu-container">%3$s</div>', // Wrap items in a div container
    'link_before'    => '<span class="link-common">',
    'link_after'     => '</span>',
    'walker'         => new Custom_Walker_Nav_Menu(), // Custom walker to handle submenus
) );

?>
<div class="section-intro">
	<div class="stm-header">
		<div class="stm-container">
			<div class="stm-logo">
				<div class="stm-image"></div>
			</div>
			<div class="column">
				<div class="column-1">
					<?php if ( $menu_items ): ?>
						<ul class="menu">
							<?php foreach ( $menu_items as $item ): ?>
								<?php if ( $item->menu_item_parent == 0 ): // It's a top-level item ?>
									<li class="menu-item">
                        <span class="link">
                            <a href="<?php echo esc_url( $item->url ); ?>">
                                <?php echo esc_html( $item->post_title ); ?>
                            </a>
                        </span>
										<?php
										// Check if this item has children
										$submenu_items = array_filter( $menu_items, function ( $submenu_item ) use ( $item ) {
											return $submenu_item->menu_item_parent == $item->ID;
										} );
										if ( ! empty( $submenu_items ) ): ?>
											<ul class="submenu">
												<?php foreach ( $submenu_items as $submenu_item ): ?>
													<li class="submenu-item">
														<a href="<?php echo esc_url( $submenu_item->url ); ?>">
															<?php echo esc_html( $submenu_item->post_title ); ?>
														</a>
													</li>
												<?php endforeach; ?>
											</ul>
										<?php endif; ?>
									</li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
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

