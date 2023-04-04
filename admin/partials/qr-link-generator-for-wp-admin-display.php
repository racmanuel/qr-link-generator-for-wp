<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://racmanuel.dev
 * @since      1.0.0
 *
 * @package    Qr_Link_Generator_For_Wp
 * @subpackage Qr_Link_Generator_For_Wp/admin/partials
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="cmb-row">
    <div id="qr-link-generator-for-wp">
        <p style="text-align: <?php echo $align; ?>;">
            <img src="<?php echo $qrcode->render($text) ?>" alt="QR Code" width="<?php echo $size . 'px'; ?>" />
            <br>
            <a href="<?php echo $qrcode->render($text); ?>" download="QR_Code.png"
                class="display: <?php echo $class; ?>">
                <button type="button" class="button"
                    style="background-color: <?php echo $button_background_color; ?>; color: <?php echo $button_color ?>;"><?php echo $text_download ?></button>
            </a>
        </p>
    </div>
</div>