(function($) {
	'use strict';

	/**
	 * Main Swatches object.
	 */
	var NiasSwatches = {

		/**
		 * Initialize the swatches.
		 */
		init: function() {
			$(document).ready(NiasSwatches.ready);
		},

		/**
		 * Document ready handler.
		 */
		ready: function() {
			$('.variations_form').each(function() {
				var $form = $(this);
				NiasSwatches.initForm($form);
			});
		},

		/**
		 * Initialize a single variation form.
		 * @param {jQuery} $form The form element.
		 */
		initForm: function($form) {
			// Bind click event to swatch items
			$form.on('click', '.ns-vr-item', function(e) {
				e.preventDefault();
				NiasSwatches.handleSwatchClick($(this), $form);
			});

			// When WooCommerce updates the form, we update our swatches
			$form.on('woocommerce_update_variation_form', function() {
				NiasSwatches.updateAvailability($form);
			});

			// Set initial state
			NiasSwatches.updateAvailability($form);
            NiasSwatches.updateInitialSelection($form);
		},

		/**
		 * Handle a click on a swatch item.
		 * @param {jQuery} $swatch The clicked swatch element.
		 * @param {jQuery} $form The parent form.
		 */
		handleSwatchClick: function($swatch, $form) {
			if ($swatch.hasClass('ns-vr-item--disabled')) {
				return;
			}

			var $attributeWrapper = $swatch.closest('.ns-vr-attribute');
			var attributeSlug = $attributeWrapper.data('attribute-slug');
			var termSlug = $swatch.data('term-slug');
			var $select = $form.find('select[name="' + attributeSlug + '"]');

			if ($swatch.hasClass('ns-vr-item--selected')) {
				// Deselect
				$select.val('').trigger('change');
				$swatch.removeClass('ns-vr-item--selected');
			} else {
				// Select
				$swatch.siblings().removeClass('ns-vr-item--selected');
				$swatch.addClass('ns-vr-item--selected');
				$select.val(termSlug).trigger('change');
			}
		},

		/**
		 * Update the enabled/disabled state of swatches based on the hidden dropdowns.
		 * @param {jQuery} $form The parent form.
		 */
		updateAvailability: function($form) {
			$form.find('.variations select').each(function() {
				var $select = $(this);
				var attributeSlug = $select.attr('name');
				var $swatches = $form.find('.ns-vr-attribute[data-attribute-slug="' + attributeSlug + '"] .ns-vr-item');

				$select.find('option').each(function() {
					var $option = $(this);
					var value = $option.val();

					if (value === '') {
						return; // Skip placeholder
					}

					var $swatch = $swatches.filter('[data-term-slug="' + value + '"]');

					if ($option.is(':disabled')) {
						$swatch.addClass('ns-vr-item--disabled');
					} else {
						$swatch.removeClass('ns-vr-item--disabled');
					}
				});
			});
		},

        /**
         * Selects the swatches that correspond to the initial values of the hidden selects.
         * @param {jQuery} $form The parent form.
         */
        updateInitialSelection: function($form) {
            $form.find('.variations select').each(function() {
                var $select = $(this);
                var selectedValue = $select.val();
                if (selectedValue) {
                    var attributeSlug = $select.attr('name');
                    var $swatch = $form.find('.ns-vr-attribute[data-attribute-slug="' + attributeSlug + '"] .ns-vr-item[data-term-slug="' + selectedValue + '"]');
                    $swatch.addClass('ns-vr-item--selected');
                }
            });
        }
	};

	NiasSwatches.init();

})(jQuery);
