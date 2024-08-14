<?php
/*
Template Name: Custom Single
*/

?>
<div class="main-container">
	<?php get_header(); ?>

	<?php if ( have_posts() ): ?>
		<?php while ( have_posts() ): the_post(); ?>
			<?php if ( has_post_thumbnail() ):
				$page_bg = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
			endif; ?>
			<div class="stm-main">
				<div class="">
					<?php the_content(); ?>
				</div>
			</div>
		<?php endwhile; ?>
	<?php endif; ?>

	<?php get_footer(); ?>
</div>
