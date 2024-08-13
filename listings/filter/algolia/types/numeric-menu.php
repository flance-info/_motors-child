<?php

$random_digit = get_random_digit();

?>
<ais-numeric-menu
	:attribute="filter.slug"
	:items="mileageOptions"
>
	<div slot-scope="{ items, refine }" class="stm-accordion-single-unit">
		<a class="title collapsed" data-toggle="collapse" :href="'#' + filter.slug + '-<?php echo $random_digit ?>'" aria-expanded="false">
			<div class="filter-title">
				<h5>
					<span class="filter-title__text">
						{{ filter.title }}
					</span>
				</h5>
			</div>
			<span class="minus"></span>
		</a>
		<div class="stm-accordion-content">
			<div :id="filter.slug + '-<?php echo $random_digit ?>'" aria-expanded="false" style="height: 0px;"
					 :class="filter.slug + '-<?php echo $random_digit ?>'"
					 class="content collapse"
			>
				<ul v-if="items.length" class="ais-RefinementList-list stm-accordion-content-wrapper">
					<li v-for="(item, index) in items"
							:key="index"
							:class="{
									'is-refined': item.isRefined,
									'are-children-refined': item.isPseudoRefined,
									'is-pseudo-refined': item.isPseudoRefined,
								}"
					>
						<label
							@click.prevent="refineHierarchy(refine, item); toggleActive(item)">
							<div class="radio">
									<span :class="{ checked: item.isRefined }">
											<input type="radio" v-model="tick"/>
									</span>
							</div>
							<span>{{ item.label }}</span>
						</label>
					</li>
				</ul>
			</div>
		</div>
	</div>
</ais-numeric-menu>


