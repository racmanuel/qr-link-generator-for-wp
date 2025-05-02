import tippy from "tippy.js";
import "tippy.js/dist/tippy.css"; // optional for styling

(function ($) {
  "use strict";

  $(function () {
    var qrcode = new QRCode("qr-link-generator-for-wp-qr-code");

    function makeCode() {
      var qr_input_value = document.getElementById(
        "qr-link-generator-for-wp-input-value",
        {
          width: 80,
          height: 80,
          colorDark: "#000000",
          colorLight: "#ffffff",
        }
      );

      if (!qr_input_value.value) {
        qr_input_value.focus();
        return;
      }

      qrcode.makeCode(qr_input_value.value);
    }

    makeCode();

    $("#qr-link-generator-for-wp-input-value")
      .on("blur", function () {
        makeCode();
      })
      .on("keydown", function (e) {
        if (e.keyCode === 13) {
          e.preventDefault(); // Solo previene el submit en Enter
          makeCode();
        }
      });

    tippy("#qr-link-generator-for-wp-input-value", {
      arrow: true,
      theme: "translucent",
      delay: 500,
      content:
        typeof ajax_object !== "undefined"
          ? ajax_object.Tooltip
          : "Insert your content to generate a QR Code.",
    });
  });
})(jQuery);
