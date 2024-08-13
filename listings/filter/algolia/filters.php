<div v-for="(filter, index) in filters" :key="index">
	<div v-if="filter.type == 'numeric-menu'">
		<?php get_template_part('listings/filter/algolia/types/numeric-menu')?>
	</div>
	<div v-else-if="filter.type == 'range-input'">
		<?php get_template_part('listings/filter/algolia/types/range-input')?>
	</div>
	<div v-else>
		<?php get_template_part('listings/filter/algolia/types/checkboxes')?>
	</div>
</div>
<?php get_template_part('listings/filter/algolia/types/menu')?>

<?php get_template_part('listings/filter/algolia/types/keyword-search')?>
