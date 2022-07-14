(function ($) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).on('load', function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practice to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(function () {

		var qrcode = new QRCode("qr-link-generator-for-wp-qr-code");

		function makeCode() {
			var qr_input_value = document.getElementById("qr-link-generator-for-wp-input-value", {
				width: 80,
				height: 80,
				colorDark: '#000000',
				colorLight: '#ffffff',
			});

			if (!qr_input_value.value) {
				qr_input_value.focus();
				return;
			}

			qrcode.makeCode(qr_input_value.value);
		}

		makeCode();

		$("#qr-link-generator-for-wp-input-value").
		on("blur", function () {
			makeCode();
		}).
		on("keydown", function (e) {
			if (e.keyCode == 13) {
				makeCode();
			}
		});
	});
})(jQuery);