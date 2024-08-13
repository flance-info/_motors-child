<?php

if (function_exists('vc_map')) {
	add_action('init', 'register_child_elements');
}

function register_child_elements()
{
	vc_map([
		'name' => __('Algolia Search Filter', 'motors-child'),
		'base' => 'stm_algolia_filter',
		'category' => __('STM', 'motors'),
		'params' => [
			[
				'type' => 'param_group',
				'heading' => __('Items', 'motors'),
				'param_name' => 'items',
				'params' => [
					[
						'type' => 'stm_autocomplete_vc',
						'heading' => __('Pre-Selected category', 'motors'),
						'param_name' => 'taxonomy',
						'description' => __('Type slug of the category (don\'t delete anything from autocompleted suggestions)',
							'motors')
					],
				],
			],
			[
				'type' => 'css_editor',
				'heading' => __('Css', 'motors'),
				'param_name' => 'css',
				'group' => __('Design options', 'motors')
			]
		]
	]);

	vc_map(array(
		'html_template' => get_stylesheet_directory() . '/vc_templates/stm_wcmap_parts_search.php',
		'name' => __('STM Parts Search', 'stm-woocommerce-motors-auto-parts'),
		'base' => 'stm_wcmap_parts_search',
		'icon' => 'stm_wcmap_parts_search',
		'category' => __('STM Auto Parts', 'stm-woocommerce-motors-auto-parts'),
		'params' => array(
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'heading' => __('Title', 'stm-woocommerce-motors-auto-parts'),
				'param_name' => 'title'
			),
			array(
				'type' => 'css_editor',
				'heading' => __('Css', 'stm-woocommerce-motors-auto-parts'),
				'param_name' => 'css',
				'group' => __('Design options', 'stm-woocommerce-motors-auto-parts')
			)
		)
	));

	vc_map(array(
		'html_template' => get_stylesheet_directory() . '/vc_templates/stm_wcmap_icon_filter.php',
		'name' => __('STM Icon Filter', 'stm-woocommerce-motors-auto-parts'),
		'base' => 'stm_wcmap_icon_filter',
		'icon' => 'stm_wcmap_icon_filter',
		'category' => __('STM Auto Parts', 'stm-woocommerce-motors-auto-parts'),
		'params' => array(
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'heading' => __('Title', 'stm-woocommerce-motors-auto-parts'),
				'param_name' => 'title'
			),
			array(
				'type' => 'dropdown',
				'heading' => __('Select Filter Type', 'stm-woocommerce-motors-auto-parts'),
				'param_name' => 'filter_type',
				'value' => array(
					'By Attributes' => 'atts',
					'By Categories' => 'cats'
				)
			),
			array(
				'type' => 'checkbox',
				'heading' => __('Show count', 'stm-woocommerce-motors-auto-parts'),
				'param_name' => 'show_count',
				'value' => array(
					__('Yes', 'stm-woocommerce-motors-auto-parts') => 'yes',
				),
				'std' => 'yes'
			),
			array(
				'type' => 'css_editor',
				'heading' => __('Css', 'stm-woocommerce-motors-auto-parts'),
				'param_name' => 'css',
				'group' => __('Design options', 'stm-woocommerce-motors-auto-parts')
			)
		)
	));


}


if (class_exists('WPBakeryShortCodesContainer')) {
	class WPBakeryShortCode_Stm_Algolia_Filter extends WPBakeryShortCode
	{
	}
}
