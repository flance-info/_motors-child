const filters = [
	{
		title: 'Search',
		slug: 'terms',
		attr: 'for_faceting.terms',
		hasChild: true,
		sortBy: 'sortByAsc',
		hidden_child: true,
		checkbox_parent: true
	},
	{
		title: 'Categories',
		slug: 'product_cat',
		attr: 'for_faceting.product_cat',
		hasChild: true,
		sortBy: 'sortByAsc'
	},
	{
		title: 'Make',
		slug: 'make',
		attr: 'make',
		hasChild: false,
		sortBy: 'sortByAsc'
	},
	{
		title: 'Model',
		slug: 'model',
		attr: 'model',
		hasChild: true,
		sortBy: 'sortByAsc'
	},

	{
		title: 'Year',
		slug: 'year',
		type: 'checkboxes',
		attr: 'year',
		sortBy: 'sortByAsc'
	},

	/*
	{
		title: 'Color',
		slug: 'pa_block-color',
		attr: 'for_faceting.pa_block-color',
		hasChild: false,
		sortBy: 'sortByAsc'
	},
	{
		title: 'Body',
		slug: 'pa_body',
		attr: 'for_faceting.pa_body',
		hasChild: false,
		sortBy: 'sortByAsc'
	},
	{
		title: 'Condition',
		slug: 'pa_new-or-used',
		attr: 'for_faceting.pa_new-or-used',
		hasChild: false,
		sortBy: 'sortByAsc'
	},
	{
		title: 'Bindings',
		slug: 'bindings',
		hasChild: false,
		attr: 'for_faceting.bindings',
		sortBy: 'sortByAsc'
	},

	{
		title: 'Price',
		slug: 'price',
		hasChild: false,
		attr: 'price',
		type: 'range-input',
		min: 0,
		max: 10000000,
	},

	 */
]

export default filters
