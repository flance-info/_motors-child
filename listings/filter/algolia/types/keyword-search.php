<?php $random_digit = get_random_digit(); ?>
<div class="stm-accordion-single-unit">
	<a class="title" data-toggle="collapse" href="#search-<?php echo $random_digit?>" aria-expanded="true">
		<h5>Keyword Search</h5>
		<span class="minus"></span>
	</a>
	<div class="stm-accordion-content">
		<div class="content collapse in" id="search-<?php echo $random_digit?>" aria-expanded="true">
			<ais-search-box>
				<div slot-scope="{currentRefinement, isSearchStalled, refine}" class="input-group">
					<input @keyup.enter="onKeyWordSearch(refine, searchQuery)" placeholder="Search here..." style="border-width: 1px;" type="search" class="noSpinner form-control" v-model.lazy="searchQuery">
					<span class="input-group-btn">
						<button @click="onKeyWordSearch(refine, searchQuery)" style="height: 39px;" class="btn" type="button">Search</button>
					</span>
				</div>
			</ais-search-box>
		</div>
	</div>
</div>
