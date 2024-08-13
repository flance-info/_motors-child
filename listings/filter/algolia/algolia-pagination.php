<!-- PAGINATION -->
<div class="pagination" style="width: 100%;">
	<ais-pagination
		:class-names="{
			'ais-Pagination' : 'stm_ajax_pagination stm-blog-pagination'
		}"
		:padding="1"
		v-on:page-change="onPageChange">

		<ul class="page-numbers"
			slot-scope="{
			  currentRefinement,
			  nbPages,
			  pages,
			  isFirstPage,
			  isLastPage,
			  refine,
			  createURL
			}">
			<div class="prev_next">
				<li >
					<a class="pages"
					   style="width: 65px;"
					   @click.prevent="refine(currentRefinement - 1)">
						Prev
					</a>
				</li>
				<li >
					<a class="pages"
					   style="width: 65px;"
					   @click.prevent="refine(currentRefinement + 1)">
						Next
					</a>
				</li>
			</div>
			<li v-if="(currentRefinement - 2) > 0 || currentRefinement === 2">
				<a class="pages" @click.prevent="refine(0)">
					1
				</a>
			</li>
			<li v-if="(currentRefinement - 2) > 0">
				...
			</li>
			<li v-for="page in pages" :key="page">
				<a class="pages"
				   :style="{ backgroundColor: page === currentRefinement ? '#000000' : '#ffcc12' }"
				   @click.prevent="refine(page)"
				>
					{{ page + 1 }}
				</a>
			</li>
			<li v-if="(currentRefinement + 3) < nbPages">
				...
			</li>
			<li v-if="(currentRefinement + 2) < nbPages">
				<a class="pages" @click.prevent="refine(nbPages)">
					{{nbPages}}
				</a>
			</li>
		</ul>

	</ais-pagination>
	<ais-hits-per-page
		:items="hitsPerPage"
		>
		<select slot-scope="{ items, refine }"
			v-model="perPage"
			@change="refine(perPage); onPageChange();"
			class="no-select2"
			style="opacity: 1; visibility: visible;">
			<option
				v-for="item in items"
				:key="item.value"
				:value="item.value"
				:selected="item.value==25 ? 'selected' : ''">
				{{ item.label }}
			</option>
		</select>
	</ais-hits-per-page>
</div>
<!-- END PAGINATION -->
