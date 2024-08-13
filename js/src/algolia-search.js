import Vue from 'vue';
import InstantSearch from 'vue-instantsearch'
import filters from './filters.js'
import classNames from './utils'
import Cleave from 'vue-cleave-component/src/index';
Vue.use(InstantSearch, Cleave);

(function ($) {
	var app = new Vue({
		el: '#app',
		components: {
			Cleave
		},
		data: {
			searchClient: algoliasearch(
				algolia_credentials.algolia_key,
				algolia_credentials.algolia_search_token
			),
			preDefined: {},
			filters: filters,
			classNames: classNames,
			tick: false,
			activeItem: {},
			refs: {},
			checked: [],
			sortOption: 'product',
			canClearAll: false,
			viewType: 'list',
			aroundLatLng: '',
			zip: '',
			radius: 160934,
			searchQuery: '',
			preloader: false,
			preDefFunc(helper) {
				this.preloader = true;
				if(app.preDefined){
					for(var i in app.preDefined){
						helper.addDisjunctiveFacetRefinement(i, app.preDefined[i].join(','));
					}
				}
				helper.search();
				app.preDefined = {}
				this.preloader = false;
			},
			routing: {
				stateMapping: {
					stateToRoute(uiState) {
						let args = {
							query: uiState.query,
							filter_make:
								uiState.refinementList &&
								uiState.refinementList.make &&
								uiState.refinementList.make.join('~'),
							filter_model:
								uiState.refinementList &&
								uiState.refinementList.model &&
								uiState.refinementList.model.join('~'),
							'filter_part-year':
								uiState.refinementList &&
								uiState.refinementList.year &&
								uiState.refinementList.year.join('~'),
							'filter_part-number':
								uiState.refinementList &&
								uiState.refinementList['pa_part-number'] &&
								uiState.refinementList['pa_part-number'].join('~'),
							page: uiState.page
						}
						return args;
					},
					routeToState(routeState) {
						let args = {
							query: routeState.query,
							refinementList: {
								'make': routeState.filter_make && routeState.filter_make.split('~'),
								'model': routeState.filter_model && routeState.filter_model.split('~'),
								'year': routeState['filter_part-year'] && routeState['filter_part-year'].split('~'),
								'for_faceting.pa_part-number': routeState['filter_part-number'] && routeState['filter_part-number'].split('~')
							},
							page: routeState.page
						}
						return args;
					}
				},
				router: instantsearch.routers.history(),
			},
			perPage: 12,
			sortByAsc: ['name:asc'],
			sortByDesc: ['name:desc'],
			sortBy: [
				{value: 'product', label: 'Last First', default: true},
				// {value: 'listings_date_dsc', label: 'Newest First'},
				// {value: 'listings_date_asc', label: 'Oldest First'},
				// {value: 'listings_price_dsc', label: 'Price High to Low'},
				// {value: 'listings_price_asc', label: 'Price Low to High'},
				// {value: 'listings_mileage_asc', label: 'Mileage Low to High'},
				// {value: 'listings_mileage_dsc', label: 'Mileage High to Low'},
			],
			exterior: [
				'colors.lvl0',
				'colors.lvl1',
			],
			hitsPerPage: [
				{label: '12 per page', value: 12, default: true},
				{label: '24 per page', value: 24},
				{label: '48 per page', value: 48},
				{label: '96 per page', value: 96},
			],
			show: false,
			excludedAtts: ['sold'],
			min: 0,
			max: 10000000,
			inputMin: '',
			inputMax: '',
			tt: false,
			range: {
				min: 0,
				max: 10000000
			},
			priceError: false,
			priceErrorMessage: '',
			showMinPrice: false,
			showMaxPrice: false,
			options: {numeral: true, numeralPositiveOnly: true, numeralIntegerScale: 10, numeralDecimalScale: 0}
		},
		methods: {
			convertSlug(text) {
				return text
					.toLowerCase() // LowerCase
					.replace(/\s+/g, "-") // space to -
					.replace(/&/g, `-and-`) // & to and
					.replace(/--/g, `-`); // -- to -
			},
			applyPriceFilter(refineFn) {
				let message = '',
						error = false

				if (!this.inputMin && !this.inputMax) {
					error = true
					message = 'Enter Min or Max value!'
				} else if (+this.inputMax > this.range.max) {
					error = true
					message = 'Max Value must be less than or equal ' + this.formatPrice(this.range.max)
				} else if (+this.inputMin < this.range.min || +this.inputMin > +this.inputMax && this.inputMax > 0) {
					error = true
					message = 'Min Value must be less than or equal ' + this.formatPrice(this.inputMax)
				}

				this.$refs.errorMsg.forEach(el => {
					el.innerText  = message
				})

				if (!error) {

					refineFn({
						min: this.inputMin || this.range.min,
						max: this.inputMax || this.range.max
					})

					if (this.inputMax) {
						setTimeout(()=> {
							this.showMaxPrice = true
						}, 450)
					}else {
						this.showMaxPrice = false
					}

					if (this.inputMin) {
						setTimeout(()=> {
							this.showMinPrice = true
						}, 450)
					}else {
						this.showMinPrice = false
					}

					if (!this.canClearAll) {
						setTimeout(()=> {
							this.canClearAll = true
						}, 450)
					}

				}

			},
			view(value) {
				this.viewType = value;
			},
			onKeyWordSearch(refineFn, searchQuery) {
				if (searchQuery !== '') {
					this.canClearAll = true
					refineFn(searchQuery)
					this.onPageChange()
				}
			},
			onPageChange() {
				const c = document.documentElement.scrollTop || document.body.scrollTop;
				if (c > 0) {
					window.scrollTo(0, 0);
				}
			},
			getLocation(zip) {
				var geocoder = new google.maps.Geocoder();
				var lat = '';
				var lng = '';
				if (zip) {
					geocoder.geocode({'address': zip}, function (res, status) {
						if (status == google.maps.GeocoderStatus.OK) {
							console.log('yes');
							lat = res[0].geometry.location.lat();
							lng = res[0].geometry.location.lng();
							app.aroundLatLng = lat + ', ' + lng;
						} else {
							console.log('nope');
						}
					})
				} else {
					app.aroundLatLng = ''
				}
			},
			refined(val) {
				if (this.checked.includes(val)) {
					var index = this.checked.indexOf(val)
					if (index > -1) {
						this.checked.splice(index, 1)
					}
				} else {
					this.checked.push(val)
				}
				if (this.checked.length > 0) {
					$('.moreColors').delay(1000).slideDown(300);
				} else {
					$('.moreColors').hide();
				}
			},
			toggleActive(item) {
				if (this.activeItem[item.label]) {
					this.removeActiveItem(item);
					return;
				}
				this.addActiveItem(item);
			},
			addActiveItem(item) {
				this.activeItem = Object.assign({}, this.activeItem, {[item.label]: item.value})
			},
			removeActiveItem(item) {
				if (item.attribute === 'price') {
					if (item.operator === '>=') {
						this.showMinPrice = false
					}
					if (item.operator === '<=') {
						this.showMaxPrice = false
					}
				}
				if (item.value === this.searchQuery) {
					this.searchQuery = ''
				}
				delete this.activeItem[item.label];
				this.activeItem = Object.assign({}, this.activeItem);
			},
			initialRefs() {
				this.refs = $('input[name="refs"]').val();
				this.activeItem = Object.assign({}, this.activeItem, {[this.refs]: this.refs})
			},
			clearRefs() {
				this.activeItem = {}
				this.searchQuery = '';
				this.preDefined = {};
			},
			selectedCount(items) {
				let count = 0
				items.forEach(parent => {
					if (parent.isRefined) {
						count++
					}
					if (parent.refinedChildren) {
						count += parent.refinedChildren
					}
				})

				return count
			},

			transformItems(attribute) {

				if (!algoliaTermsHierachy || !algoliaTermsHierachy[attribute]) {
					console.log('not hierarchy', attribute)
					return items => items
				}
				let vm = this;
				return function (items) {

					const parentMap = algoliaTermsHierachy[attribute];
					const childrenNames = Object.keys(parentMap);
					const result = [];

					// if(attribute === 'product_cat'){
					// 	console.log(parentMap);
					// 	console.log(childrenNames);
					// }



					if(attribute === 'terms') {

						// console.log(parentMap);
						// console.log(childrenNames);
						items.forEach(item1 => {
							item1.children = []
							item1.refinedChildren = 0


							// Make -> Model -> Year
							if (typeof parentMap[item1.value] === 'object') {
								result.push(item1)
								// Find models
								items.forEach(item2 => {
									if (typeof parentMap[item1.value][item2.value] === 'object') {
										item2.children = []
										item2.refinedChildren = 0
										// model Found
										item2.isPseudoRefined = item1.isRefined && !item2.isRefined
										item1.children.push(item2)
										if (item2.isRefined) {
											item1.refinedChildren++
										}

										// Find Years
										items.forEach(item3 => {
											if (typeof parentMap[item1.value][item2.value][item3.value] !== 'undefined') {
												// year Found
												item3.isPseudoRefined = item2.isRefined && !item3.isRefined
												item2.children.push(item3)
												if (item3.isRefined) {
													item2.refinedChildren++
												}
											}
										})
										item2.areChildrenRefined = item2.refinedChildren > 0
										item2.isPseudoRefined = item2.areChildrenRefined && !item1.isRefined
									}
								})
							}
							item1.areChildrenRefined = item1.refinedChildren > 0
							item1.isPseudoRefined = item1.areChildrenRefined && !item1.isRefined
						})
						return result;
					}

					// first level is responsible for gathering parents
					items.forEach(item1 => {

						// Parents skipped
						if (childrenNames.indexOf(item1.value) > -1) {
							return
						}

						item1.children = []
						item1.refinedChildren = 0
						result.push(item1)

						// second level is responsible for gathering children
						items.forEach(item2 => {

							if (item2.value && parentMap[item2.value] === item1.value) {
								item2.isPseudoRefined = item1.isRefined && !item2.isRefined
								item1.children.push(item2)
								if (item2.isRefined) {
									item1.refinedChildren++
								}
							}
						})

						item1.areChildrenRefined = item1.refinedChildren > 0
						item1.isPseudoRefined = item1.areChildrenRefined && !item1.isRefined

					})
					return result
				}
			},
			isAddedToCompare(listing_id){
				return $.cookie('compare_ids['+ listing_id +']')
			},
			displayRefinements(items) {
				let  withoutPriceLabels = [];
					items.forEach( item => {
					if (item.attribute !== 'price') {
						withoutPriceLabels.push(item)
					}
				})

				if ( (withoutPriceLabels && withoutPriceLabels.length) || (this.showMinPrice || this.showMaxPrice) ) {
					this.canClearAll = true
				}else {
					this.canClearAll = false
				}

				return items.map(item => ({
					...item,
					label: item.label.toUpperCase(),
				}));
			},
			refineHierarchy(refineFn, parent, item2) {
				if (item2) {
					refineFn(item2.value)
					// in case when some children are going to be checked
					// the parent should be excluded from refinement
					// to show listing only for selected children
					if (!item2.isRefined && parent.isRefined) {
						refineFn(parent.value)
					}
				} else {
					if (parent.children && parent.children.length) {
						if (parent.isPseudoRefined) {
							parent.children.forEach(child => {
								if (child.isRefined) {
									refineFn(child.value)
								}
							})
						} else {
							parent.children.forEach(child => {
								// uncheck child only if it is checked now
								if (!child.isRefined) {
									refineFn(child.value)
								}
							})
						}
					} else {
						refineFn(parent.value);
					}
				}
			},
			formLabel(item){
				if (item.attribute === "mileage") {
					return item.value + ' miles or less';
				}
				if (item.attribute === "sold") {
					return 'Sold';
				}
				if (item.attribute === "price") {
					let operator = item.operator === '>=' ? 'Min' : 'Max';
					return operator + ' Price: ' +  this.formatPrice(item.value)
				}
				return item.label;
			},
			formatPrice(value) {
				const formatter = new Intl.NumberFormat('en-US', {
					style: 'currency',
					currency: 'USD',
				});
				return formatter.format(value).replace(/\D00$/, '');
			}
		},
		filters: {
			commaSeparator: function (value) {
				return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			},
		},
		directives: {
			carousel: {
				inserted: function (el) {
					// $(el).owlCarousel({
					// 	items: 1,
					// 	nav: false,
					// 	dots: false,
					// });
				},
			}
		},
		mounted: function () {
			let self = this;

			window.addEventListener('load', () => {
				app.initialRefs();
			})
			let temp = JSON.parse(this.$el.getAttribute('data-preDefined'));
			for(var i in temp){
				if(i !== 'model'){
					let attr = '';
					self.filters.forEach(function(el,k){
						if(i === el.slug){
							attr = el.attr;
						}
					});
					if(attr !== ''){
						self.preDefined[attr] = temp[i];
					}
				}else{
					self.preDefined[i] = temp[i];
				}
			}

			const queryString = window.location.search;

			if (queryString.includes('models') || queryString.includes('query')) {
				this.canClearAll = true
			}

			var vm = this;
			$(this.$el).find('select')
			// init select2
				.select2()
				.trigger("change")
				// emit event on change.
				.on("change", function() {
					vm.$emit("input", this.value);
				});
		},
		watch: {
			value: function(value) {
				// update value
				$(this.$el)
					.val(value)
					.trigger("change");
			},
			options: function(options) {
				// update options
				$(this.$el)
					.empty()
					.select2({ data: options });
			},
			zip: {
				handler: function (val) {
					let self = this;
					if(val !== ''){
						let found = false;
						self.sortBy.forEach(function(el, i){
							if(el.value === 'listings_zip'){
								found = true;
							}
						});
						if(!found){
							this.sortBy.push({value: 'listings_zip', label: 'Distance Closest First'});
						}
					}else{
						this.sortBy.forEach(function(el, i){
							if(el.value === 'listings_zip'){
								self.sortBy.splice(i, 1);
							}
						});
					}
				},
				deep: true
			}
		}
	});
	$(function () {
		$(".stm-modern-view").click(function () {
			$(".stm-modern-view").removeClass("active");
			$(this).addClass("active");
		});
		// $('select.no-select2').removeClass('select2-hidden-accessible');
		$('select.no-select2').select2('destroy');
	});
})(jQuery);
