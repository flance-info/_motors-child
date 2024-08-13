<?php
add_action( 'admin_enqueue_scripts', 'wp_enqueue_custom_admin_style' );
function wp_enqueue_custom_admin_style() {
	wp_enqueue_style( 'stm-select2', get_template_directory_uri() . '/assets/css/select2.min.css', null, STM_THEME_VERSION, 'all' );
	wp_enqueue_script( 'stm-select2-js', get_template_directory_uri() . '/assets/js/select2.full.min.js', array( 'jquery' ), STM_THEME_VERSION, true );
}


add_action('init', 'create_taxonomy');
function create_taxonomy(){
	register_taxonomy('custom-binding', 'product', array(
		'label'                 => '',
		'labels'                => array(
			'name'              => 'Custom Binding',
			'singular_name'     => 'Custom Binding',
			'search_items'      => 'Search Custom Binding',
			'all_items'         => 'All Custom Binding',
			'parent_item'       => 'Parent Custom Binding',
			'parent_item_colon' => 'Parent Custom Binding:',
			'edit_item'         => 'Edit Custom Binding',
			'update_item'       => 'Update Custom Binding',
			'add_new_item'      => 'Add New Custom Binding',
			'new_item_name'     => 'New Custom Binding',
			'menu_name'         => 'Custom Binding',
		),
		'public' => true,
		'hierarchical' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_admin_column' => false,
		'show_in_nav_menus' => false,
		'show_in_quick_edit' => false,
	) );
}

add_filter( 'manage_edit-custom-binding_columns', 'stm_add_custom_binding_columns' );
function stm_add_custom_binding_columns( $columns ) {
	$columns['make'] = 'Make';
	$columns['model'] = 'Model';
	$columns['year'] = 'Year';
	return $columns;
}

add_filter('manage_custom-binding_custom_column', 'stm_add_custom_binding_column_content',10,3);
function stm_add_custom_binding_column_content($content,$column_name,$term_id){
	switch ($column_name) {
		case 'make':
			$content = get_term_meta($term_id, 'make', true);
			break;
		case 'model':
			$content = get_term_meta($term_id, 'models', true);
			break;
		case 'year':
			$content = implode(', ', get_term_meta($term_id, 'year', false));
			break;
		default:
			break;
	}
	return $content;
}

add_action('add_meta_boxes', 'custom_binding_add_custom_box');
function custom_binding_add_custom_box() {
	$screens = array( 'product' );
	foreach ( $screens as $screen )
		add_meta_box( 'custom_binding_sectionid', 'Custom Binding', 'custom_binding_meta_box_callback', $screen );
}

function custom_binding_meta_box_callback() {
	?>
	<style type="text/css">
    .form-table.stm.custom-binding-table {
		width: 100%;
	}
	.custom-binding-table th {
		width: 100px;
	}
	.custom-binding-table.form-table.stm th,
	.custom-binding-table.form-table.stm td {
		vertical-align: top;
	}
	.custom-binding-table .binding-col-make,
	.custom-binding-table .binding-col-serie {
		width: 180px;
	}
	.custom-binding-table .select2 {
		display: block;
	}
	.custom-binding-table .binding-col-year {
	}
	.custom-binding-table .binding-col-remove {
		width: 62px;
	}
	.custom-binding-table .binding-col-year .select2.select2-container,
	.custom-binding-table .binding-col-year .select2.select2-container .select2-search--inline .select2-search__field {
		width: auto !important;
		display: block;
	}
	.custom-binding-table .binding-col-year .select2 {
		max-width: 400px;
	}
	.custom-binding-table .select2-container .select2-selection--multiple,
	.custom-binding-table .select2-container .select2-selection--single {
		min-height: 32px;
		/*height: 32px;*/
	}
	.custom-binding-table .select2-container .select2-selection--single,
	.custom-binding-table .select2-container .select2-selection--single .select2-selection__arrow{
		height: 35px;
	}
	.custom-binding-table .select2-container .select2-selection--single .select2-selection__rendered {
		line-height: 35px;
	}
	.select2-results__option {
		margin-bottom: 0;
	}
	.select2-container--default .select2-results__option[aria-disabled=true] {
		display: none;
	}

  </style>

	<input type="hidden" name="this_post_id" value="<?php the_ID(); ?>">
	<?php wp_nonce_field('custom_binding_nonce','custom_binding_nonce'); ?>

	<?php
	$terms_make_arr = stm_get_category_by_slug_all('make');
	$terms_make = array();
	if(!is_wp_error($terms_make_arr)){
		foreach ($terms_make_arr as $key => $term) {
			$terms_make[$key]['slug'] = $term->slug;
			$terms_make[$key]['name'] = $term->name;
		}
	}


	$terms_serie_arr = stm_get_category_by_slug_all('model');
	$terms_serie = array();
	if(!is_wp_error($terms_serie_arr)) {
		foreach ($terms_serie_arr as $key => $term) {
			$terms_serie[$key]['slug'] = $term->slug;
			$terms_serie[$key]['name'] = $term->name;
		}
	}

	$terms_year_arr = stm_get_category_by_slug_all('part-year');
	$terms_year = array();
	if(!is_wp_error($terms_year_arr)) {
		foreach ($terms_year_arr as $key => $term) {
			$terms_year[$key]['slug'] = $term->slug;
			$terms_year[$key]['name'] = $term->name;
		}
	}
	?>

	<table class="form-table stm custom-binding-table">
    <tbody>

    <?php
	$terms = wp_get_post_terms( get_the_ID(), 'custom-binding', array(
		'hide_empty' => false,
	) );
	$count_exist_terms = 0;
	if(!empty($terms)):
		foreach ($terms as $key => $value):
			$term_id = $value->term_id;
			$term_meta_make = get_term_meta( $term_id, 'make', true );
			$term_meta_models = get_term_meta( $term_id, 'models', true );
			$term_meta_year = get_term_meta( $term_id, 'year' );
			?>
			<tr class="exist-tr">
            	<th><?php _e('Select:', 'motors-child'); ?></th>
				<td class="binding-col-make">
					<select name="binding-make[]" class="js-select">
						<option value=""><?php _e('Select Make', 'motors-child'); ?></option>
						<?php foreach ($terms_make as $key => $value): ?>
							<?php
							$selected = '';
							if($value['slug'] == $term_meta_make) {
								$selected = 'selected';
							}
							?>
							<option value="<?php echo $value['slug']; ?>" <?php echo $selected; ?>>
								<?php echo $value['name']; ?>
							</option>
						<?php endforeach; ?>
              		</select>
            	</td>
				<td class="binding-col-serie">
					<select name="binding-models[]" class="js-select">
						<option value=""><?php _e('Select Serie', 'motors-child'); ?></option>
						<?php foreach ($terms_serie as $key => $value): ?>
							<?php
							$selected = '';
							if($value['slug'] == $term_meta_models) {
								$selected = 'selected';
							}
							?>
							<option value="<?php echo $value['slug']; ?>" <?php echo $selected; ?>>
								<?php echo $value['name']; ?>
							</option>
						<?php endforeach; ?>
					</select>
				</td>
				<td class="binding-col-year">
					<select
						name="binding-ca-year[<?php echo $count_exist_terms; ?>][]"
						class="js-select"
						data-placeholder="<?php _e('Select Year', 'motors-child'); ?>"
						multiple>
						<?php foreach ($terms_year as $key => $value): ?>
							<?php
							$selected = '';
							if(in_array($value['slug'], $term_meta_year)) {
								$selected = 'selected';
							}
							?>
							<option value="<?php echo $value['slug']; ?>" <?php echo $selected; ?>>
								<?php echo $value['name']; ?>
							</option>
						<?php endforeach; ?>
					</select>
				</td>
				<td class="binding-col-remove">
					<button type="button" class="button button-danger delete-binding">
						<?php _e('Delete', 'motors-child'); ?>
					</button>
				</td>
			</tr>
			<?php
			$count_exist_terms++;
		endforeach;
	endif;
	?>

	<tr class="first-tr">
		<th><?php _e('Select:', 'motors-child'); ?></th>
		<td class="binding-col-make">
			<select name="binding-make[]" class="js-select">
				<option value=""><?php _e('Select Make', 'motors-child'); ?></option>
				<?php foreach ($terms_make as $key => $value): ?>
					<option value="<?php echo $value['slug']; ?>"><?php echo $value['name']; ?></option>
				<?php endforeach; ?>
			</select>
		</td>
		<td class="binding-col-serie">
			<select name="binding-models[]" class="js-select">
				<option value=""><?php _e('Select Serie', 'motors-child'); ?></option>
				<?php foreach ($terms_serie as $key => $value): ?>
					<option value="<?php echo $value['slug']; ?>"><?php echo $value['name']; ?></option>
				<?php endforeach; ?>
			</select>
		</td>
		<td class="binding-col-year">
			<select
				name="binding-ca-year[<?php echo $count_exist_terms; ?>][]"
				class="js-select"
				data-placeholder="<?php _e('Select Year', 'motors-child'); ?>"
				multiple>
				<?php foreach ($terms_year as $key => $value): ?>
					<option value="<?php echo $value['slug']; ?>"><?php echo $value['name']; ?></option>
				<?php endforeach; ?>
			</select>
		</td>
		<td class="binding-col-remove">
			<button type="button" class="button button-danger delete-binding">
				<?php _e('Delete', 'motors-child'); ?>
			</button>
		</td>
	</tr>
    </tbody>
    <tfoot>
    <tr>
		<td colspan="5" style="text-align: right;">
			<button type="button" class="button button-primary add-binding">
				<?php _e('Add binding', 'motors-child'); ?>
			</button>
		</td>
	</tr>
    </tfoot>
	</table>


	<?php $bind_tax = json_encode(stm_data_binding()); ?>

	<script type="text/javascript">
    (function($){
		var stmTaxRelations = <?php echo $bind_tax; ?>;

		var insert_content = [
			'<tr class="added-tr">',
				'<th><?php _e('Select:', 'motors-child'); ?></th>',
				'<td class="binding-col-make">',
					'<select name="binding-make[]" class="js-select">',
						'<option value=""><?php _e('Select Make', 'motors-child'); ?></option>',
						<?php foreach ($terms_make as $key => $value): ?>
							'<option value="<?php echo $value['slug']; ?>"><?php echo $value['name']; ?></option>',
						<?php endforeach; ?>
					'</select>',
				'</td>',
				'<td class="binding-col-serie">',
					'<select name="binding-models[]" class="js-select">',
						'<option value=""><?php _e('Select Serie', 'motors-child'); ?></option>',
						<?php foreach ($terms_serie as $key => $value): ?>
							'<option value="<?php echo $value['slug']; ?>"><?php echo $value['name']; ?></option>',
						<?php endforeach; ?>
					'</select>',
				'</td>',
				'<td class="binding-col-year">',
					'<select name="binding-ca-year[<?php echo $count_exist_terms + 1; ?>][]" class="js-select" data-placeholder="<?php _e('Select Year', 'motors-child'); ?>" multiple>',
						<?php foreach ($terms_year as $key => $value): ?>
							'<option value="<?php echo $value['slug']; ?>"><?php echo $value['name']; ?></option>',
						<?php endforeach; ?>
					'</select>',
				'</td>',
				'<td class="binding-col-remove">',
					'<button type="button" class="button button-danger delete-binding"><?php _e('Delete', 'motors-child'); ?></button>',
					'</td>',
			'</tr>'
		].join("\n");

		$(document).ready(function(){

			$('.js-select').select2();

			function counter_added(){
				var counter = $(document).find('.custom-binding-table').find('tr:not(.added-tr)').length;
				if(counter > 0) {
					counter = counter - 1;
				}
				$(document).find('.custom-binding-table').find('.added-tr').each(function(){
					$(this).find('.binding-col-year').find('select').attr('name', 'binding-ca-year['+counter+'][]');
					counter++;
				});
			}

			$('.custom-binding-table .add-binding').on('click', function(){
				$('.custom-binding-table tbody').append(insert_content);
				$(document).find('.custom-binding-table').find('.added-tr').find('select').select2();
				counter_added();
			});

			$(document).find('.custom-binding-table').on('click', '.delete-binding', function(){
				$(this).parents('tr').find('select').select2("destroy");
				$(this).parents('tr').remove();
				counter_added();
			});

			$(document).find('.custom-binding-table').on('change', 'select', function () {
				/*Remove disabled*/

				var stmCurVal = $(this).val();
				var stmCurSelect = $(this).attr('name');
				stmCurSelect = stmCurSelect.match(/binding-(.+)\[\]/)[1];

				if (stmTaxRelations[stmCurSelect]) {

					var key = stmTaxRelations[stmCurSelect]['dependency'];
					$(this).parents('tr').find('select[name="binding-' + key + '[]"]').val('');
					if (stmCurVal == '') {
						$(this).parents('tr').find('select[name="binding-' + key + '[]"]').find('option').each(function () {
							$(this).removeAttr('disabled');
						});
					} else {
						var allowedTerms = stmTaxRelations[stmCurSelect][stmCurVal];

						if (typeof(allowedTerms) == 'object') {
							$(this).parents('tr').find('select[name="binding-' + key + '[]"]').find('option').removeAttr('disabled');

							$(this).parents('tr').find('select[name="binding-' + key + '[]"]').find('option').each(function () {
								var optVal = $(this).val();
								if (optVal != '' && $.inArray(optVal, allowedTerms) == -1) {
									$(this).attr('disabled', '1');
								} else {

								}
							});
						} else {
							$(this).parents('tr').find('select[name="binding-' + key + '[]"]').val(allowedTerms);
						}
					}

					if ($(this).parents('tr').find('select[name="binding-' + key + '[]"]').length > 0) {
						$(this).parents('tr').find('select[name="binding-' + key + '[]"]').select2("destroy");
					}
					if ($(this).parents('tr').find('select[name="binding-' + key + '[]"]')) {
						// $(this).parents('tr').find('select[name="binding-' + key + '"]').find('option:not([disabled=disabled]):first').prop('selected', true);
						$(this).parents('tr').find('select[name="binding-' + key + '[]"]').select2();

					}
				}
			});
		});

	})(jQuery);
  </script>

	<?php
}

add_action( 'save_post', 'custom_binding_save_postdata' );
function custom_binding_save_postdata( $post_id ) {
	if ( ! isset( $_POST['custom_binding_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['custom_binding_nonce'], 'custom_binding_nonce' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	$binding_make = $binding_models = $binding_year = '';
	if(isset($_POST['binding-make'])) {
		$binding_make = $_POST['binding-make'];
	}
	if(isset($_POST['binding-models'])) {
		$binding_models = $_POST['binding-models'];
	}
	if(isset($_POST['binding-ca-year'])) {
		$binding_year = $_POST['binding-ca-year'];
	}

	$term_insert = array();
	foreach ($binding_make as $key => $value) {
		$term_insert[$key]['make'] = $value;
	}
	foreach ($binding_models as $key => $value) {
		$term_insert[$key]['models'] = $value;
	}
	foreach ($binding_year as $key => $value) {
		$term_insert[$key]['year'] = $value;
	}

	$terms_ids = array();
	foreach ($term_insert as $key => $value) {
		$slug_array = array();
		if(!empty($value['make'])) {
			$slug_array[] = $value['make'];
		}
		if(!empty($value['models'])) {
			$slug_array[] = $value['models'];
		}
		if(!empty($value['year'])) {
			$slug_array[] = implode("-", $value['year']);
		}

		if(empty($value['make']) && empty($value['models'])) {
			continue;
		}
		$slug = implode('-', $slug_array);


		$term_exists = term_exists( $slug, 'custom-binding' );

		if($term_exists == false || $term_exists == 0) {
			$term_obj = wp_insert_term(
				$slug, // the term
				'custom-binding', // the taxonomy
				array(
					'description'=> '',
					'slug' => $slug,
				)
			);

			if(!empty($term_obj) && is_array($term_obj)) {
				$term_id = $term_obj['term_id'];

				if(!empty($term_id)) {
					$terms_ids[] = $term_id;
					update_term_meta( $term_id, 'make', $value['make'] );
					update_term_meta( $term_id, 'models', $value['models'] );

					delete_term_meta($term_id, 'year');
					foreach ($value['year'] as $item) {
						add_term_meta( $term_id, 'year', $item );
					}
				}
			}
		} else {
			$term_id = $term_exists['term_id'];
			$terms_ids[] = $term_id;
		}
	}

	wp_set_post_terms( $post_id, $terms_ids, 'custom-binding', false );
	// die();

}


if(!function_exists('stm_get_category_by_slug_all')){
	function stm_get_category_by_slug_all($slug, $isAddACar = false){
		if (!empty($slug)) {
			$terms_args = array(
				'orderby' => 'name',
				'order' => 'ASC',
				'hide_empty' => (get_theme_mod('hide_empty_category', false) && !$isAddACar) ? true : false,
				'fields' => 'all',
				'pad_counts' => apply_filters('stm_get_term_pad_counts', true),
			);
			$terms = get_terms('pa_'.$slug, $terms_args);

			return $terms;
		}
	}
}


if ( !function_exists( 'stm_data_binding' ) ) {
	function stm_data_binding( $allowAll = false )
	{
		$attributes = stm_get_car_parent_exist();

		$bind_tax = array();
		$depends = array();
		foreach ( $attributes as $attr ) {

			$parent = $attr['listing_taxonomy_parent'];
			$slug = $attr['slug'];

			$depends[] = array( 'parent' => $parent, 'dep' => $slug );


			if ( !isset( $bind_tax[$parent] ) ) {
				$bind_tax[$parent] = array();
			}

			$bind_tax[$slug] = array(
				'dependency' => $parent,
				'allowAll' => $allowAll,
				'options' => [],
			);

			/** @var WP_Term $term */

			foreach ( stm_get_category_by_slug_all( $slug, true ) as $term ) {
				$deps = array_values( array_filter( (array)get_term_meta( $term->term_id, 'stm_parent' ) ) );

				$bind_tax[$slug]['options'][] = array(
					'value' => $term->slug,
					'label' => $term->name,
					'count' => $term->count,
					'deps' => $deps,
				);
			}
		}

		$sortDeps = array();

		for ( $q = 0; $q < count( $depends ); $q++ ) {
			if ( $q == 0 ) {
				$sortDeps[] = $depends[$q]['parent'];
				$sortDeps[] = $depends[$q]['dep'];
			} else {
				if ( in_array( $depends[$q]['dep'], $sortDeps ) ) {
					array_splice( $sortDeps, array_search( $depends[$q]['dep'], $sortDeps ), 0, $depends[$q]['parent'] );
				} elseif ( in_array( $depends[$q]['parent'], $sortDeps ) ) {
					array_splice( $sortDeps, array_search( $depends[$q]['parent'], $sortDeps ) + 1, 0, $depends[$q]['dep'] );
				} elseif ( !in_array( $depends[$q]['parent'], $sortDeps ) ) {
					array_splice( $sortDeps, 0, 0, $depends[$q]['parent'] );
					array_splice( $sortDeps, count( $sortDeps ), 0, $depends[$q]['dep'] );
				}
			}
		}

		$newBindTax = array();

		foreach ( $sortDeps as $val ) {
			$newBindTax[$val] = $bind_tax[$val];
		}

		return apply_filters( 'stm_data_binding', $newBindTax );
	}
}


function stm_get_car_parent_exist()
{

	$car_listing = array();
	$options = get_option('stm_vehicle_listing_options');
	if (!empty($options)) {
		foreach ($options as $key => $option) {
			if (!empty($options[$key]['listing_taxonomy_parent'])) {
				$car_listing[] = $option;
			}
		}
	}

	return $car_listing;
}


add_filter('woocommerce_product_query', 'stm_listing_pre_get_vehicles_child', 50);
function stm_listing_pre_get_vehicles_child($query)
{
	$query_vars = $query->query_vars;
	if (!empty($_GET['s'])) {
		$query_vars['s'] = $_GET['s'];
	}

	if (!empty($_GET['filter_make'])) {
		foreach ($query_vars['tax_query'] as $key => $value) {
			if(!isset($value['taxonomy'])) continue;
			if($value['taxonomy'] == 'pa_make') {
				unset($query_vars['tax_query'][$key]);
			} else {
				continue;
			}
		}
	}

	if (!empty($_GET['filter_model'])) {
		foreach ($query_vars['tax_query'] as $key => $value) {
			if(!isset($value['taxonomy'])) continue;
			if($value['taxonomy'] == 'pa_model') {
				unset($query_vars['tax_query'][$key]);
			} else {
				continue;
			}
		}
	}

	if (!empty($_GET['filter_part-year'])) {
		foreach ($query_vars['tax_query'] as $key => $value) {
			if(!isset($value['taxonomy'])) continue;
			if($value['taxonomy'] == 'pa_part-year') {
				unset($query_vars['tax_query'][$key]);
			} else {
				continue;
			}
		}
	}

	$min_ca_year = !empty($_GET['min_part-year']) ? intval($_GET['min_part-year']) : false;
	$max_ca_year = !empty($_GET['max_part-year']) ? intval($_GET['max_part-year']) : false;
	$ca_year = !empty($_GET['filter_part-year']) ? intval($_GET['filter_part-year']) : false;
	$make = !empty($_GET['filter_make']) ? $_GET['filter_make'] : false;
	$model = !empty($_GET['filter_model']) ? $_GET['filter_model'] : false;

	$meta_query = array(
		'relation' => 'OR',
		array(
			'key' => 'universal',
			'value' => '1',
		),
		'binding' => array(
			'relation' => 'AND',
		)
	);
	if ( $make ) {
		$meta_query['binding'][] = array(
			'key' => 'make',
			'value' => $make,
		);
	}

	if ( $model ) {
		$meta_query['binding'][] = array(
			'key' => 'models',
			'value' => $model,
		);
	}

	if ( $ca_year ) {
		$meta_query['binding'][] = array(
			'key' => 'year',
			'value' => $ca_year,
		);
	}

	if ( $min_ca_year || $max_ca_year ) {
		$value = $compare = '';
		switch ( true ) {
		case ! empty( $min_ca_year ) && ! empty( $max_ca_year ):
			$value = array( $min_ca_year, $max_ca_year );
			$compare = 'BETWEEN';
			break;
		case ! empty( $min_ca_year ):
			$value = $min_ca_year;
			$compare = '>=';
			break;
		case ! empty( $max_ca_year ):
			$value = $min_ca_year;
			$compare = '<=';
			break;
		}

		$meta_query['binding'][] = array(
			'key' => 'year',
			'value' => $value,
			'compare' => $compare,
		);
	}

	$binding_query = array(
		'hide_empty' => true,
		'meta_query' => $meta_query,
	);
	$terms = get_terms( 'custom-binding',  $binding_query);

	if($terms){
		$query_vars['tax_query'][] = array(
			array(
				'taxonomy' => 'custom-binding',
				'field'    => 'id',
				'terms'    => array_values( wp_list_pluck( $terms, 'term_id' ) ),
				'compare' => 'IN',
			)
		);
	}

	$query->query_vars = $query_vars;
	return $query;
}


add_action('wp_ajax_get_child_categories', 'stm_get_child_categories_custom_binding');
add_action('wp_ajax_nopriv_get_child_categories', 'stm_get_child_categories_custom_binding');
function stm_get_child_categories_custom_binding() {
	$make = $_POST['make'];
	$model = $_POST['model'];
	$year = $_POST['year'];

	$meta_query = array(
		'relation' => 'OR',
		array(
			'key' => 'universal',
			'value' => '1',
		),
		'binding' => array(
			'relation' => 'AND',
		)
	);
	if ( $make ) {
		$meta_query['binding'][] = array(
			'key' => 'make',
			'value' => $make,
		);
	}

	if ( $model ) {
		$meta_query['binding'][] = array(
			'key' => 'models',
			'value' => $model,
		);
	}

	if ( $year ) {
		$meta_query['binding'][] = array(
			'key' => 'year',
			'value' => $year,
		);
	}

	$binding_query = array(
		'fields' => 'ids',
		'hide_empty' => true,
		'meta_query' => $meta_query,
	);
	$bindings = get_terms( 'custom-binding',  $binding_query);

	$args = [
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'fields' => 'ids',
	];

	$args['tax_query'][] = [
		'taxonomy' => 'custom-binding',
		'field' => 'id',
		'terms' => $bindings,
		'operator' => 'IN'
	];

	$ids = get_posts($args);
	$opt_cats = [];
	$temp = wp_get_object_terms($ids, 'product_cat');

	$opt_cats = stm_get_cat_hierarchy();

	wp_send_json(['opt_cats' => $opt_cats, 'ids' => $ids, 'temp' => $temp]);
	exit;
}

function inventoryCatLink($cat_name) {
	$link = get_the_permalink( 16404 );
	if($cat_name == 'Cameras') return get_the_permalink( 70683 );
	return "$link?category=$cat_name";
}

add_filter('stm_all_cats_filter', function($html, $atts){
	$includeCats = explode(',', $atts['cats']);
	array_walk($includeCats, 'trim_value');
	$includeCats = array_filter($includeCats, function ($value) {
		return $value !== '';
	});

	$cats = get_terms(array(
		'orderby' => 'id',
		'order' => 'ASC',
		'fields' => 'all',
		'show_count' => 0,
		'hierarchical' => 1,
		'hide_empty' => 0,
		'taxonomy' => 'product_cat'
	));

	$categoryHierarchy = array();
	stm_wcmap_sort_terms_hierarchicaly($cats, $categoryHierarchy);

	$class = (wp_is_mobile()) ? 'stm_wcmap_mobile_mm' : '';
	$out = '<ul class="' . esc_attr($class) . '">';

	$link = get_the_permalink( 16404 );
	foreach ($categoryHierarchy as $k => $cat) {
		if ($cat->slug == 'uncategorized') continue;

		if (!empty($includeCats)) {
			if (array_search(trim($cat->slug), $includeCats) === false) continue;
		}

		$hasChild = (!empty($cat->children)) ? 'stm_wcmap_mm_has_children' : '';

		$out .= '<li class="' . esc_attr($hasChild) . '">';
		if (wp_is_mobile() && !empty($cat->children)) {
			$out .= '<a href="' . inventoryCatLink($cat->name). '">' . $cat->name . '</a><span class="icon-ap-arrow"></span>';
		} else {
			$out .= '<a href="' . inventoryCatLink($cat->name). '"><span class="icon-ap-arrow"></span>' . $cat->name . '</a>';
		}

		$advertImgId = get_term_meta($cat->term_id, 'stm_wcmap_image', true);
		$advertImg = wp_get_attachment_image_url($advertImgId, 'stm-wcmap-210-260');
		$advertLink = get_term_meta($cat->term_id, 'stm_banner_link', true);

		if (!empty($cat->children)) {
			$out .= '<div class="stm-wcmap-subcats-content">';
			$out .= '<ul class="subcat-list">';

			foreach ($cat->children as $q => $subCat) {
				$out .= '<li>';
				$out .= '<a class="subcat heading-font" href="' . inventoryCatLink($subCat->name) . '">' . $subCat->name . '</a>';

				if (!empty($subCat->children)) {
					$out .= '<div class="stm-wcmap-subsubcats-content">';
					$out .= '<ul class="subsubcat-list">';
					foreach ($subCat->children as $w => $subSubCat) {
						$out .= '<li>';
						$out .= '<a class="subSubCat normal_font" href="' . inventoryCatLink($subSubCat->name) . '">' . $subSubCat->name . '</a>';
						$out .= '</li>';
					}

					$out .= '</ul>';
					$out .= '</div>';
				}

				$out .= '</li>';
			}

			$out .= '</ul>';
			if (!empty($advertImg)) {
				$out .= '<div class="cat-advert">';
				$out .= (!empty($advertLink)) ? '<a href="' . $advertLink . '">' : '';
				$out .= '<img src="' . $advertImg . '" />';
				$out .= (!empty($advertLink)) ? '</a>' : '';
				$out .= '</div>';
			}
			$out .= '</div>';
		}

		$out .= '</li>';
	}

	$out .= '</ul>';
	return $out;
},100,2);
