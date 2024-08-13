<?php
wp_enqueue_script('algolia-search-inventory');
wp_enqueue_script('slide-panel');

$atts = vc_map_get_attributes($this->getShortcode(), $atts);

extract($atts);
$items = vc_param_group_parse_atts($atts['items']);
$preDefined = [];
foreach ($items as $item) {
	if(!empty($item['taxonomy'])){
		$temp = explode(' | ', $item['taxonomy']);
		$term = get_term_by('slug', $temp[0], $temp[1]);
		$preDefined[$temp[1]][] = $term->name;
	}
}
?>
<div class="row stm-template-auto_parts woocommerce stm-wcmap-shop" id="app" v-cloak data-preDefined="<?php echo htmlspecialchars(json_encode($preDefined), ENT_QUOTES, 'UTF-8') ?>">

	<ais-instant-search
		index-name="product"
		v-bind:search-client="searchClient"
		:search-function="preDefFunc"
		:routing="routing">

		<ais-configure
			:max-values-per-facet.camel="100"
			:get-ranking-info.camel="true"
			:around-lat-lng.camel="aroundLatLng"
			:around-radius.camel="radius">
		</ais-configure>



		<div class="col-md-9 col-md-push-3 col-sm-12" id="listings-result" :class="{loader : preloader}">

			<!-- FILTER ACTIONS -->
			<div class="action-bar-wrap">
				<div class="left">
					<h4>
						<span class="orange">
							<ais-stats>
								<span slot-scope="{nbHits}">{{nbHits}}</span>
							</ais-stats>
						</span>
						<span>Products Found</span>
					</h4>
				</div>
				<!-- sort by -->
				<div class="right">
					<div class="woocommerce-notices-wrapper"></div>
					<span>Sort by:</span>
					<ais-sort-by
						:class-names="classNames.sortBy"
						:items="sortBy">

						<select class="no-select2" style="opacity: 1; visibility: visible;"
							slot-scope="{ items, currentRefinement, refine }"
							v-model.lazy="sortOption"
							v-on:change="refine(sortOption);">
							<option
								v-for="item in items"
								:key="item.value"
								:value="item.value">

								<div>{{item.label}}</div>
							</option>
						</select>
					<ais-sort-by/>
				</div>
				<!-- end sort by -->
			</div>

			<!-- mobile filter controls -->
			<div id="tesla-results" class="stm-view-by visible-xs-block  sticky">
				<div class="filter-button js-cd-panel-trigger" data-panel="main" style="display: inline-block;">
					<i class="fa fa-sliders abd-modern-filter-icon-button"></i>
					<span>Filter</span>
				</div>
				<div class="stm-modern-filter-found-cars" style="display: inline-block; padding-bottom: 0;">
					<h4>
						<span class="orange">
							<ais-stats>
								<span slot-scope="{nbHits}">{{nbHits}}</span>
							</ais-stats>
						</span>
						<span>Products Found</span>
					</h4>

				</div>
			</div>



			<!-- mobile filters -->
			<div class="cd-panel cd-panel--from-left js-cd-panel-main" style="z-index: 999;">
				<header class="cd-panel__header" style="display: flex;">
					<h5 class="found-teslas js-cd-close" style="padding: 15px 20px 15px 15px;">
						<i class="fa fa-chevron-left js-cd-close" aria-hidden="true"></i>
						<ais-stats>
							<span class="js-cd-close" slot-scope="{nbHits}">{{nbHits}}</span>
						</ais-stats>
						<span class="js-cd-close">
							Products
						</span>
					</h5>
					<ais-clear-refinements
						:excluded-attributes="excludedAtts">
						<div v-on:click.prevent="refine"
						 class="ais-CurrentRefinements-list"
						 slot-scope="{ canRefine, refine, createURL }"
						>
							<span v-on:click="clearRefs();" class="reset-filters ">
								Reset Filters
							</span>
						</div>
					</ais-clear-refinements>
					<div class="results-button js-cd-close">
						<span v-on:click="onPageChange()" class="js-cd-close" style="line-height: 40px;">Show results</span>
					</div>
				</header>
				<div class="cd-panel__container">
					<div id="filter-menu" class="cd-panel__content col-md-3 col-sm-12 sidebar-sm-mg-bt abd-mobile-modern-filters" style="display: block !important;">
						<?php get_template_part('listings/filter/algolia/filters') ?>
					</div>
				</div>
			</div>
			<!-- end mobile filters -->


			<!-- end mobile filter controls -->
			<div class="modern-filter-badges" style="padding-top: 10px;">
					<ais-clear-refinements
						:excluded-attributes="excludedAtts">
						<ul
							class="ais-CurrentRefinements-list stm-filter-chosen-units-list"
							slot-scope="{ canRefine, refine, createURL }">
							<li class="ais-CurrentRefinements-item"
								v-on:click.prevent="refine"
								v-if="canRefine"
								v-show="canClearAll"
								style="margin-bottom: 20px;"
								>
								<span v-on:click="clearRefs();" class="ais-CurrentRefinements-category">
                  					Clear All
								</span>
							</li>
						</ul>
					</ais-clear-refinements>

					<ais-current-refinements
						:excluded-attributes="excludedAtts"
						:transform-items="displayRefinements">
						<template slot="item" slot-scope="{ item, refine, createURL }">
							<div style="display: flex; margin: 0 0 5px 0;">
								<div v-for="refinement in item.refinements"
									:key="[
										refinement.attribute,
										refinement.type,
										refinement.value,
										refinement.operator
									].join(':')"
									>

									<div v-if="refinement.attribute !== 'price'"
										:class="'stm-filter-chosen-units-list'">
										{{ formLabel(refinement) }}
										<i
										v-on:click.prevent="refine(refinement); removeActiveItem(refinement)"
										class="fa fa-close stm-clear-listing-one-unit"></i>
										<input type="hidden" name="refs" :value="refinement.value"/>
									</div>

									<div v-else-if="refinement.attribute !== 'sold'">
										<div v-if="refinement.attribute === 'price' && refinement.operator == '>='"
											v-show="showMinPrice"
											:class="'stm-filter-chosen-units-list price-badge'">
											{{ formLabel(refinement) }}
											<i
												v-on:click.prevent="refine(refinement); removeActiveItem(refinement)"
												class="fa fa-close stm-clear-listing-one-unit"></i>
											<input type="hidden" name="refs" :value="refinement.value"/>
										</div>
										<div v-else
											v-show="showMaxPrice"
											:class="'stm-filter-chosen-units-list price-badge'">
												{{ formLabel(refinement) }}
												<i
													v-on:click.prevent="refine(refinement); removeActiveItem(refinement)"
													class="fa fa-close stm-clear-listing-one-unit"></i>
												<input type="hidden" name="refs" :value="refinement.value"/>
										</div>

									</div>
								</div>
							</div>
						</template>
					</ais-current-refinements>
				</div>
			<!-- end current refinements(badges) -->


			<!-- END FILTER ACTIONS -->




			<!-- HITS -->
			<div class="">
				<?php get_template_part('listings/filter/algolia/algolia-grid-view') ?>
			</div>
			<!-- END HITS -->
		<?php get_template_part('listings/filter/algolia/algolia-pagination') ?>
		</div>

		<!-- DESKTOP FILTERS -->
		<div class="col-md-3 col-md-pull-9 hidden-sm hidden-xs sidebar-sm-mg-bt abd-full-modern-filters">
			<?php get_template_part('listings/filter/algolia/filters') ?>
		</div>
		<!-- END DESKTOP FILTERS -->

	</ais-instant-search>
</div>
