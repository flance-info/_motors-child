(function ($) {
	function _find(list, prop, val) {
		var item;
		for (var i in list) {
			item = list[i];
			if (item[prop] === val) {
				return item;
			}
		}

		return null;
	}

	function _filter(list, prop, val) {
		var items = [];
		for (var i in list) {
			if (list[i][prop] === val) {
				items.push(list[i]);
			}
		}

		return items;
	}

	new Vue({
		el: '#cruxdevice',
		data: {
			config: cruxdevice_config,
			state: {
				step: 1,
				device_id: null,
				radio_id: null,
				make_id: null,
				model_id: null,
				make: null,
				model: null,
				year: null,
				radio: null
			},
			devices: [],
			makes: [],
			models: [],
			years: [],
			radios: [],
			manual: null,
			switches: []
		},
		created: function () {
			var _this = this;
			$.getJSON(this.config.api_uri + 'devices', function (response) {
				_this.devices = response.data;
				_this.$forceUpdate();
			});
		},
		methods: {
			deviceChanged: function (device_id) {
				this.state.device_id = parseInt(device_id);
			},
			validate: function () {
				if (!this.state.make || !this.state.model || !this.state.year || !this.state.radio) {
					alert('Please, select all fields: make, model, year, radio.');
					return false;
				}

				return true;
			},
			step1: function () {
				this.state.step = 1;
			},
			step2: function () {
				this.state.step = 2;
				var _this = this;
				$.getJSON(this.config.api_uri + 'makes?per_page=999', { device_id: _this.state.device_id }, function (response) {
					_this.makes = response.data;

					_this.makes.sort(function(a,b){
						let textA = a.name.toUpperCase();
						let textB = b.name.toUpperCase();

  						return textA.localeCompare(textB);
					});

				});
				$.getJSON(this.config.api_uri + 'radios', { device_id: _this.state.device_id }, function (response) {
					_this.radios = response.data;
				});
			},
			step3: function () {
				if (!this.validate()) {
					return;
				}

				this.state.step = 3;

				var _this = this;
				$.getJSON(this.config.api_uri + 'manuals', {
					device_id: this.state.device_id,
					model_id: this.state.model_id,
					year: this.state.year
				}, function (response) {
					if (response.data.length) {
						_this.manual = response.data[0];
						_this.prepareManual();
					}
					else {
						_this.manual = null;
					}
				});
			},
			step4: function () {
				if (!this.validate()) {
					return;
				}

				this.state.step = 4;
			},
			prepareManual: function () {
				var groups = [];

				function makeCss(color) {
					color = color.split('/');
					var css;
					if (color.length === 1) {
						css = { 'background': getColor(color[0]) };
						if (color[0] === 'white') {
							css['border'] = '1px solid black';
							css['border-style'] = 'solid none solid';
						}
					}
					else {
						var c1 = getColor(color[0]);
						var c2 = getColor(color[1]);
						// repeating-linear-gradient( 35deg, black, black 10px, white 10px, white 16px )
						css = { 'background': 'repeating-linear-gradient( 35deg, ' + c1 + ', ' + c1 + ' 10px, ' + c2 + ' 10px, ' + c2 + ' 16px' };
					}

					return css;
				}

				function getColor(name) {
					var colors = {
						'light blue': '#99f',
						'dark blue': '#009',
						'light green': '#9f9',
						'dark green': '#090'
					};

					return colors[name] || name;
				}

				var ctrlColors = {
					'constant': 'yellow',
					'ground': 'black',
					'accessory': 'red/white',
					'can-': 'black/pink',
					'can+': 'pink',
					'ibus': 'white/red',
					'class2': 'violet/white',
					'swc1': 'orange/black',
					'swc2': 'yellow/black',
					'swc3': 'green/white'
				};

				for (var img_id in this.manual.data.imgs) {
					var conns = _filter(this.manual.data.conns, 'img', '' + img_id);
					for (var cid in conns) {
						var conn = conns[cid];
						if (!conn.c_color) {
							conn.c_color = ctrlColors[conn.ctrl] || 'black';
						}
						conn.c_ccs = makeCss(conn.c_color);
						conn.css = conn.color.split(' or ');
						conn.css = makeCss(conn.css[0]);
					}
					groups.push({
						img: this.manual.data.imgs[img_id],
						conns: conns
					});
				}

				this.manual.groups = groups;
			}
		},
		watch: {
			'state.make_id': function () {
				var _this = this;
				_this.state.make = _find(this.makes, 'id', _this.state.make_id);
				_this.models = [];
				_this.years  = [];
				_this.state.model_id = null;
				_this.state.year = null;
				$.getJSON(this.config.api_uri + 'makes/' + _this.state.make_id + '/models/available', { device_id: _this.state.device_id }, function (response) {
					_this.models = response;

					_this.models.sort(function(a,b){
						let textA = a.name.toUpperCase();
						let textB = b.name.toUpperCase();

  						return textA.localeCompare(textB);
					});
				});
			},
			'state.model_id': function () {
				this.state.model = _find(this.models, 'id', this.state.model_id);
				this.years = this.state.model.years;
				this.years.sort(function(a, b) {
					return a - b;
				})
				this.state.year = null;
				console.log(this.years);
			},
			'state.radio_id': function () {
				this.state.radio = _find(this.radios, 'id', this.state.radio_id);
			}
		},
		components: {
			select2: {
				props: ['value'],
				template: '<select v-bind:value="value"><slot></slot></select>',
				mounted: function () {
					var _this = this;
					$(this.$el).select2().change(function (event) {
						_this.$emit('input', event.target.value);
					});
				},
				watch: {
					value: function(val) {
						setTimeout( () => {
							$(this.$el).parents('.row').find('select').select2('destroy').select2()
						}, 300);

					}
				}
			},
			owl: {
				template: '#cruxdevice-owl',
				props: ['source'],
				data: function () {
					return {
						devices: this.source
					}
				},
				mounted: function () {
					this.$nextTick(this.init);
				},
				watch: {
					source: function (newVal, oldVal) {
						if (newVal === oldVal) {
							return;
						}
						this.refresh();
					}
				},
				methods: {
					refresh: function () {
						var _this = this;
						this.devices = [];
						this.$nextTick(function () {
							_this.devices = _this.source;
							_this.$forceUpdate();
							this.$nextTick(this.init);
						});
					},
					init: function () {
						var _this = this;
						if (!$(_this.$el).is('div')) {
							return;
						}

						function setDevice(index) {
							var device_id = $(_this.$el).find('[rel]').eq(index).attr('rel');
							_this.$emit('change', device_id);
						}
						setDevice(0);

						if ($(_this.$el).children().length < 2){
							return;
						}

						$(_this.$el).owlCarousel({
							center: true,
							items: 3,
							loop: true,
							margin: 10,
							nav: true,
							dots: false,
							responsive: {
								600: {
									items: 3
								},
								300: {
									items: 1
								}
							}
						}).on('changed.owl.carousel', function (event) {
							setDevice(event.item.index);
						});
					}
				}
			}
		}
	})
})(jQuery);