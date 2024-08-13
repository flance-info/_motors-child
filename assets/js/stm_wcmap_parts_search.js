(function ($) {
	$.initialize(".select2-results__option[role='group']", function(index, optionGroup) {
		let optGroupName = $(this).attr("aria-label");
		let select2ResultsId = $(this).closest("ul.select2-results__options").attr("id");
		if(select2ResultsId) {
			let ptrn = /^select2-([\w]+)-[a-z0-9]+-results/i;
			let res = ptrn.exec(select2ResultsId);
			if(res && res.length > 0) {
				let relSelect2Id = res[1];
				let relOptGroup = $("select[name='" + relSelect2Id + "']").find("optgroup[label='" +optGroupName +  "'][disabled]");
				if (relOptGroup.length) {
					$(optionGroup).attr("disabled", "disabled");
					$(optionGroup).attr("aria-disabled", "true");
				}
			}
		}
	});


	$(document).ready(function () {
		$('.stm_mc-filter-selects select').select2('destroy')
		$('.stm_mc-filter-selects select').select2()

		jQuery('.wcmap-part-filter form select[name=filter_part-year]').on('change', function() {
			getChild();
		})
		function getChild() {
			let _make = jQuery('.wcmap-part-filter form select[name=filter_make]').val();
			let _model = jQuery('.wcmap-part-filter form select[name=filter_model]').val();
			let _year = jQuery('.wcmap-part-filter form select[name=filter_part-year]').val();

			let make = jQuery('.wcmap-part-filter form select[name=filter_make] option[value="'+_make+'"]').data('slug');
			let model = jQuery('.wcmap-part-filter form select[name=filter_model] option[value="'+_model+'"]').data('slug');
			let year = jQuery('.wcmap-part-filter form select[name=filter_part-year] option[value="'+_year+'"]').data('slug');

			$.ajax({
				url: window.wp_data.wcmap_ajax_url,
				type: "POST",
				dataType: 'json',
				data: 'model='+model+'&make='+make+'&year=' +year + '&action=get_child_categories',
				beforeSend: function () {
					jQuery('.wcmap-part-filter form select[name=filter_make]').prop('disabled', true);
					jQuery('.wcmap-part-filter form select[name=filter_model]').prop('disabled', true);
					jQuery('.wcmap-part-filter form select[name=filter_part-year]').prop('disabled', true);
					jQuery('.wcmap-part-filter form select[name=filter_cat]').prop('disabled', true);
				},
				success: function (msg) {
					$('.stm_mc-filter-selects select[name="filter_cat"] option').attr('disabled', '1');
					$('.stm_mc-filter-selects select[name="filter_cat"] optgroup').attr('disabled', '1');
					$('.stm_mc-filter-selects select[name="filter_cat"] option[value=""]').removeAttr('disabled');
					msg.temp.forEach(function (el) {
						var optVal = $('.stm_mc-filter-selects select[name="filter_cat"] option[value="'+el.name+'"]');
						if (optVal) {
							optVal.removeAttr('disabled');
							optVal.parents('optgroup').removeAttr('disabled');
						}
					});
					$('.stm_mc-filter-selects select[name="filter_cat"]').val('').select2('destroy').select2();
				},
				complete: function () {
					jQuery('.wcmap-part-filter form select[name=filter_make]').prop('disabled', false);
					jQuery('.wcmap-part-filter form select[name=filter_model]').prop('disabled', false);
					jQuery('.wcmap-part-filter form select[name=filter_part-year]').prop('disabled', false);
					jQuery('.wcmap-part-filter form select[name=filter_cat]').prop('disabled', false);
				},
			});
		}
	});
})(jQuery);
