<?php
class STM_ProductCategoryHierarchy extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'stm_prod_cat_hierarchy', 'description' => __('STM Product Category Hierarchy', 'motors'));
		$control_ops = array('width' => 400, 'height' => 350);
		parent::__construct('stm_prod_cat_hierarchy', __('STM Prod.Category Hierarchy', 'motors'), $widget_ops, $control_ops);
	}

	private function get_category_hierarchical() {
		$r = '';

		$args = array(
			'taxonomy' => 'product_cat',
			'parent' => 0,
			'hide_empty' => false
		);

		$next = get_terms($args);
		$category = get_queried_object();
		if ($next) {
			$r .= '<ul>';
			foreach ($next as $cat) {
				$class = [];
				if(!empty($category->term_id) && ($category->term_id == $cat->term_id || $category->parent == $cat->term_id)){
					$class[] = 'active';
				}
				$r .= '';
				$children = get_terms( ['child_of' => $cat->term_id, 'taxonomy' => 'product_cat', 'hide_empty' => false]);
				if( !empty( $children ) ) {
					$class[] = 'has_child';
					$r .= '<li class="'.implode(" ",$class).'"><span>' . $cat->name . '</span>';

					$r .= '<ul>';
					foreach ($children as $child) {
						$class = [];
						if(!empty($category->term_id) && ($category->term_id == $child->term_id || $category->parent == $child->term_id)){
							$class[] = 'active';
						}
						//$child = get_term($child_id);
						$r .= '<li class="'.implode(" ",$class).'"><a href="' . get_term_link($child->slug, $child->taxonomy) . '">' . $child->name . '</a>';
					}
					$r .= '</ul>';

				}else{
					$r .= '<li class="'.implode(" ",$class).'"><a href="' . get_term_link($cat->slug, $cat->taxonomy) . '">' . $cat->name . '</a>';
				}
				$r .= '</li>';

				//$r .= $cat->term_id !== 0 ? $this->get_category_hierarchical($cat->term_id, true) : null;
			}

			$r .= '</ul>';
		}

		return $r;
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		?>


		<?php echo $args['before_widget']; ?>

		<div class="stm-prod-cat-hierarchy">

			<?php if ( ! empty( $title ) ): ?>
				<div class="title">
						<i class="fa fa-paper-plane"></i>
					<?php echo esc_html($title); ?>
					</div>
			<?php endif; ?>

			<?php
			$product_cat = get_terms([
				'taxonomy' => 'product_cat',
				'hide_empty' => false,
			]);
			echo $this->get_category_hierarchical();
			?>

			<style>
				.stm-prod-cat-hierarchy{

				}
				.stm-prod-cat-hierarchy ul li{
					width: 100% !important;
					margin-bottom: 5px !important;
					display: block !important;
					position: relative;
				}
				.stm-prod-cat-hierarchy ul li:before,
				.stm-prod-cat-hierarchy ul li:after{
					content: "";
					width: 16px;
					height: 16px;
					display: block;
					position: absolute;
					top: 5px;
					left: 0;
				}
				.stm-prod-cat-hierarchy ul li:before{
					border: 1px solid #6c98e1;
					box-shadow: 1px 1px 3px rgba(108,152,227,0.4) inset;
				}
				.stm-prod-cat-hierarchy > ul > li.has_child:before{
					border-radius: 50%;
				}
				.stm-prod-cat-hierarchy > ul > li.has_child:after{
					background-color: #6c98e1;
					border-radius: 50%;
					height: 6px;
					width: 6px;
					left: 5px;
					top: 10px;
				}
				.stm-prod-cat-hierarchy ul li.active{

				}
				.stm-prod-cat-hierarchy ul li a,
				.stm-prod-cat-hierarchy ul li span{
					padding-left: 22px;
					position: relative;
					z-index: 10;
					font-size: 13px;
				}
				.stm-prod-cat-hierarchy ul > li > ul li a:before,
				.stm-prod-cat-hierarchy ul > li > ul li span:before{
					content: "";
					height: 1px;
					width: 10px;
					position: absolute;
					left: -10px;
					top: 8px;
					background-color: #6c98e1;
				}
				.stm-prod-cat-hierarchy ul li a:hover,
				.stm-prod-cat-hierarchy ul li a:focus{
					color: #6c98e1 !important;
				}

				.stm-prod-cat-hierarchy ul{

				}
				.stm-prod-cat-hierarchy ul > li > ul{
					margin-left: 7px !important;
					border-left: 1px solid #6c98e1;
					padding-left: 10px !important;
					margin-top: -6px !important;
					padding-top: 6px !important;
				}
				.stm-prod-cat-hierarchy ul > li > ul > li {

				}
				.stm-prod-cat-hierarchy ul > li > ul > li:before{
					border: 1px solid #6c98e1;
					border-radius: 0;
					box-shadow: 1px 1px 3px rgba(108,152,227,0.4) inset;
				}

				.stm-prod-cat-hierarchy ul > li > ul > li.active:after,
				.stm-prod-cat-hierarchy ul > li.active:not(.has_child):after{
					content: "\2713";
					background-color: transparent;
					border-radius: 0;
					box-shadow: none;
					top: 1px;
					left: 4px;
					font-weight: bolder;
					font-size: 11px;
					color: #6c98e1;
					width: 6px;
					height: 6px;
				}

			</style>
		</div>
		<?php echo $args['after_widget']; ?>



		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['shortcode'] = $new_instance['shortcode'];
		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '','shortcode' => '' ) );
		$title = $instance['title'];
		?>
		<p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'motors'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		<?php
	}
}

function register_stm_prod_cat_hierarchy() {
	register_widget( 'STM_ProductCategoryHierarchy' );
}
add_action( 'widgets_init', 'register_stm_prod_cat_hierarchy' );
