<?php

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_class = (!empty($css)) ? apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' ')) : '';

stm_wcmap_enqueue_scripts_styles('stm_wcmap_parts_search', 'stm_wcmap_parts_search');

$base_color = get_theme_mod('site_style_base_color', '#cc6119');
$custom_css = ".icon-ap-car {
                        color: {$base_color};
                }
                
                button {
                background: {$base_color};
                }
                ";
wp_add_inline_style( 'stm-wcmap-stm_wcmap_parts_search', $custom_css );

//$terms_make_arr = stm_get_category_by_slug_all('make');
$bindings = stm_get_binding_hierarchy();
$make = $model = $year = [];

$make = $bindings['makes'];
foreach ($bindings['models'] as $_make) {
	if(!empty($_make) && is_array($_make)){
		foreach ($_make as $_model) {
			$model[$_model['name']] = $_model;
		}
	}
}
foreach ($bindings['years'] as $_make) {
	if(!empty($_make) && is_array($_make)){
		foreach ($_make as $_model) {
			if(!empty($_model) && is_array($_model)){
				foreach ($_model as $_year) {
					$year[$_year['name']] = $_year;
				}
			}
		}
	}
}
asort($model);
asort($year);


//if(!is_wp_error($terms_make_arr)){
//	foreach ($terms_make_arr as $key => $term) {
//		$opt .= '<option value="' . $term->name . '" >' . ucfirst($term->name) . '</option>';
//	}
//}
?>

<div class="stm_wcmap_parts_search_wrap">
    <div class="stm_wcmap_title_wrap">
        <i class="icon-ap-car"></i>
        <h2 class="heading-font"><?php echo esc_html($atts['title']); ?></h2>
    </div>
	<?php
	$shop_page_id = 16404;//apply_filters( 'woocommerce_get_shop_page_id' , get_option( 'woocommerce_shop_page_id' ) );
	?>
	<div class="wcmap-part-filter stm_mc-filter-selects">
		<form action="<?php echo get_the_permalink($shop_page_id); ?>">
			<div class="row">
				<div class="col-md-3 col-sm-6">
					<div class="form-group">
						<select name="filter_make" class="form-control">
							<option value="">
								<?php echo esc_html__('Select Make', 'stm-woocommerce-motors-auto-parts');?>
							</option>
							<?php foreach($make as $slug => $item): ?>
								<option value="<?php echo $item['name'] ?>" data-slug="<?php echo $item['slug'] ?>">
									<?php echo $item['name'] ?>
								</option>
							<?php endforeach ?>
						</select>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<div class="form-group">
						<select name="filter_model" class="form-control">
							<option value="">
								<?php echo esc_html__('Select Model', 'stm-woocommerce-motors-auto-parts');?>
							</option>
							<?php foreach($model as $item): ?>
								<option value="<?php echo $item['name'] ?>" data-slug="<?php echo $item['slug'] ?>">
									<?php echo $item['name'] ?>
								</option>
							<?php endforeach ?>
						</select>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<div class="form-group">
						<select name="filter_part-year" class="form-control">
							<option value="">
								<?php echo esc_html__('Select Year', 'stm-woocommerce-motors-auto-parts');?>
							</option>
							<?php foreach($year as $item): ?>
								<option value="<?php echo $item['name'] ?>" data-slug="<?php echo $item['slug'] ?>">
									<?php echo $item['name'] ?>
								</option>
							<?php endforeach ?>
						</select>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<div class="form-group">
						<select name="filter_cat" class="form-control">
								<option value="">
									<?php echo esc_html__('select category', 'stm-woocommerce-motors-auto-parts');?>
								</option>
							<?php foreach(stm_get_cat_hierarchy() as $parent => $item): ?>
								<?php if(count($item)): ?>
									<option value="<?php echo $parent ?>"><?php echo $parent ?></option>
									<?php if(false): ?>
										<optgroup label="<?php echo $parent ?>">
											<?php foreach($item as $i): ?>
												<option value="<?php echo $i ?>"><?php echo $i ?></option>
											<?php endforeach ?>
										</optgroup>
									<?php endif ?>
								<?php else: ?>
									<option value="<?php echo $parent ?>"><?php echo $parent ?></option>
								<?php endif ?>
							<?php endforeach ?>
						</select>
					</div>
				</div>


				<div class="stm_mc-submit-btn">
					<div class="form-group">
						<button class="wcmap_ps_submit">
							<?php echo esc_html__('Search', 'stm-woocommerce-motors-auto-parts'); ?>
						</button>
					</div>
				</div>
			</div>
		</form>

		<script>
            (function($) {
              "use strict";
              $('document').ready(function(){
                jQuery('.wcmap_ps_submit').on('click', function(event){
                  event.preventDefault();

                  let action = jQuery('.wcmap-part-filter form').attr('action');
                  let url = action + '?filter=';

                  let make = jQuery('.wcmap-part-filter form select[name=filter_make]').val();
                  let model = jQuery('.wcmap-part-filter form select[name=filter_model]').val();
                  let year = jQuery('.wcmap-part-filter form select[name=filter_part-year]').val();
                  let parts = jQuery('.wcmap-part-filter form input[name=filter_part-number]').val();
                  let category = jQuery('.wcmap-part-filter form select[name=filter_cat]').val();

                  if(make) url = url + make;
                  if(model) url = url + ">" + model;
                  if(year) url = url + ">" + year;
                  if(parts) url = url + "&filter_part-number=" + parts;
                  if(category) url = url + "&category=" + category;
                  window.location.href = url;
                })

                var stm_make_model_binding = <?php echo json_encode($bindings); ?>;
                $('.stm_mc-filter-selects select[name="filter_make"]').on('change', function(){
                  var selected = $(this).val();
                  var allowedTerms = stm_make_model_binding['models'][selected];
                  $('.stm_mc-filter-selects select[name="filter_model"] > option').removeAttr('disabled');
                  $('.stm_mc-filter-selects select[name="filter_model"] > option').each(function () {
                    var optVal = $(this).val();

                    if (optVal !== '' && allowedTerms && !allowedTerms.hasOwnProperty(optVal)) {
                      $(this).attr('disabled', '1');
                    }
                  });
                  $('.stm_mc-filter-selects select[name="filter_model"]').val('').select2('destroy').select2();
                  $('.stm_mc-filter-selects select[name="filter_part-year"]').val('').select2('destroy').select2();
                });

                $('.stm_mc-filter-selects select[name="filter_model"]').on('change', function(){
                  var model = $(this).val();
                  var make = $('.stm_mc-filter-selects select[name="filter_make"]').val()
                  var allowedTerms = stm_make_model_binding['years'][make][model];
                  $('.stm_mc-filter-selects select[name="filter_part-year"] > option').removeAttr('disabled');
                  $('.stm_mc-filter-selects select[name="filter_part-year"] > option').each(function () {
                    var optVal = $(this).val();
                    if (optVal !== '' && !allowedTerms.hasOwnProperty(optVal)) {
                      $(this).attr('disabled', '1');
                    }
                  });
                  $('.stm_mc-filter-selects select[name="filter_part-year"]').val('').select2('destroy').select2();
                });
              });
            })(jQuery);
		</script>
	</div>
</div>
