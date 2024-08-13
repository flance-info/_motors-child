<?php $random_digit = get_random_digit(); ?>
<div class="stm-accordion-single-unit">
	<a class="title" data-toggle="collapse" href="#search-<?php echo $random_digit?>" aria-expanded="true">
		<h5>Search Menu</h5>
		<span class="minus"></span>
	</a>
	<div class="stm-accordion-content">
		<div class="content collapse in" id="search-<?php echo $random_digit?>" aria-expanded="true">
			<ais-hierarchical-menu
				:limit="200"
				:attributes="[ 'terms.lvl0', 'terms.lvl1', 'terms.lvl2' ]">
			</ais-hierarchical-menu>
		</div>
	</div>
</div>

