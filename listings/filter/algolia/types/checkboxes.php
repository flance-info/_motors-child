<?php $random_digit = get_random_digit(); ?>
<ais-refinement-list
    v-if="filter.type !== 'multi'"
	:attribute="filter.attr"
    :transform-items="transformItems(filter.slug)"
	:class-names="classNames.accordion"
	:sort-by="['name:asc']"
	operator="and"
    :limit="100">

	<div slot-scope="{ items, refine }" :class="'stm-accordion-single-unit ' + filter.attr">
		<a
			:class="{
				'title': true,
				'collapsed': filter.slug !== 'product_cat'
			}"
		   data-toggle="collapse"
		   :href="'#' + filter.slug + '-<?php echo $random_digit?>'"
		   aria-expanded="false">
			<div class="filter-title">
				<h5>
					<span class="filter-title__text">
						{{ filter.title }}
					</span>
					<span v-if="selectedCount(items)"
                        class="filter-title__selected">
						({{ selectedCount(items)  }} Selected)
					</span>
				</h5>
			</div>
			<span class="minus"></span>
		</a>
		<div class="stm-accordion-content">
			<div :id="filter.slug + '-<?php echo $random_digit?>'" aria-expanded="false"
                 :class="{
                 'content': true,
                 'collapse': filter.slug !== 'product_cat',
                 'collapse in': filter.slug == 'product_cat',
                 'hidden_child': filter.hidden_child,
                 'checkbox_parent': filter.checkbox_parent

               }">
				<transition name="fade">
					<ul v-if="items.length" class="ais-RefinementList-list stm-accordion-content-wrapper">
						<li v-for="(item, index) in items"
							:key="index"
							:class="{
								'is-refined': item.isRefined,
								'are-children-refined': item.isPseudoRefined,
								'is-pseudo-refined': item.isPseudoRefined,
								'has-children': item.children && item.children.length
							}"
							v-on:click="selectedCount(items) > 0 ? canClearAll = true : canClearAll = false">
							<label v-if="item.children && item.children.length" >
								<div class="checker">
									<span :class="{checked: item.isRefined}">
										<input type="checkbox" v-model="tick"/>
									</span>
								</div>
								<span>{{ item.value }}</span>
							</label>
							<label v-else
								@click.prevent="refineHierarchy(refine, item); toggleActive(item)">
								<div class="checker">
									<span :class="{checked: item.isRefined}">
										<input type="checkbox" v-model="tick"/>
									</span>
								</div>
								<span>{{ item.value }} <span v-if="item.count">({{ item.count }})</span></span>
							</label>
							<transition name="fade">
								<ul v-if="item.children && item.children.length"
									class="ais-RefinementList-list stm-accordion-content-wrapper">
									<li v-for="(item2, index2) in item.children"
										:key="index2"
										:style="{ fontWeight: item2.isRefined ? 'bold' : '' }"
										:class="{
										'is-refined': item2.isRefined,
										'is-pseudo-refined': item2.isPseudoRefined,
											}">
										<label @click.prevent="refineHierarchy(refine, item, item2); toggleActive(item2)">
											<div class="checker">
												<span :class="{checked: item2.isRefined}">
													<input type="checkbox" v-model="tick"/>
												</span>
											</div>
											<span>
												{{ item2.value }}
												<span v-if="item2.count">({{ item2.count }})</span>
											</span>
										</label>

										<transition name="fade">
											<ul v-if="item2.children && item2.children.length"
												class="ais-RefinementList-list stm-accordion-content-wrapper">
												<li v-for="(item3, index3) in item2.children"
													:key="index3"
													:style="{ fontWeight: item3.isRefined ? 'bold' : '' }"
													:class="{
													'is-refined': item3.isRefined,
													'is-pseudo-refined': item3.isPseudoRefined,
														}">
													<label @click.prevent="refineHierarchy(refine, item2, item3); toggleActive(item3)">
														<div class="checker">
															<span :class="{checked: item3.isRefined}">
																<input type="checkbox" v-model="tick"/>
															</span>
														</div>
														<span>
															{{ item3.value }}
															<span v-if="item3.count">({{ item3.count }})</span>
														</span>
													</label>
												</li>
											</ul>
										</transition>
									</li>
								</ul>
							</transition>
						</li>
					</ul>
					<ul  v-else class="ais-RefinementList-list stm-accordion-content-wrapper">
						<li class="ais-RefinementList-list__no-options">
							No options available.
						</li>
					</ul>
				</transition>
			</div>
		</div>
	</div>
</ais-refinement-list>

