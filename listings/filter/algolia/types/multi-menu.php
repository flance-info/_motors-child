<div class="stm-accordion-content">
	<a class="title collapsed" data-toggle="collapse" href="#ext-color" aria-expanded="false">
			<div class="filter-title">
				<h5>
					<span class="filter-title__text">
						{{ filter.title }}
					</span>
				</h5>
			</div>
			<span class="minus"></span>
		</a>
					<div class="content collapse" id="ext-color" aria-expanded="false" style="height: 0px;">
						<ais-hierarchical-menu
							:attributes="[
								'colors.lvl0',
								'colors.lvl1',
							  ]"
							:sort-by="['name:asc']"
							:class-names="{
								'ais-HierarchicalMenu-list' : 'stm-accordion-content-wrapper',
								'ais-HierarchicalMenu-item' : 'stm-single-unit'
							}"
							separator=">"
							:show-parent-level="true"
						>
							<div slot-scope="{ items, refine, createURL }">
								<ul class="stm-accordion-content-wrapper"
										style="list-style: none;">
									<li class="stm-single-unit"
											v-for="item in items"
											:key="item.value"
											:style="{ fontWeight: item.isRefined ? 600 : 400 }"
									>
										<label
											:style="{ fontWeight: item.isRefined ? 'bold' : '' }"
											@click.prevent="refine(item.value);"
										>
											<div class="checker">
												<span :class="{checked: item.isRefined}">
													<input type="checkbox" v-model="tick"/>
												</span>
											</div>
											{{item.label}}
										</label>
										<ul class="stm-accordion-content-wrapper"
												v-if="item.data"
												style="list-style: none; padding-bottom: 0;">
											<li class="stm-single-unit"
													v-for="child in item.data"
													:key="child.value"
													:style="{ fontWeight: child.isRefined ? 600 : 400 }"
											>
												<label
													:style="{ fontWeight: child.isRefined ? 'bold' : '' }"
													@click.prevent="refine(child.value);"
												>
													<div class="checker">
														<span :class="{checked: child.isRefined}">
															<input type="checkbox" v-model="tick"/>
														</span>
													</div>
													{{child.label}}
												</label>
											</li>
										</ul>
									</li>
								</ul>
							</div>
						</ais-hierarchical-menu>
					</div>
				</div>

