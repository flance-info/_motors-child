<?php
?>

<ais-hierarchical-menu
	v-else
	:attributes="filter.attr"
	:sort-by="['name:asc']"
	:class-names="classNames.hierarchicalMenu"
	separator=">"
	:show-parent-level="false"
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
						<span>{{ item.value }} ({{ item.count }})</span>
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
								<input type="radio" v-model="tick"/>
							</span>
							</div>
							<span>{{ child.value }} ({{ child.count }})</span>
						</label>
						</li>
					</ul>
					</li>
				</ul>
				</div>
			</ais-hierarchical-menu>

