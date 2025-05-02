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
        <p style="text-align: <?php echo esc_attr($align); ?>;">
            <img 
                src="<?php echo esc_url($qrcode->render($text)); ?>" 
                alt="<?php echo esc_attr__('QR Code', 'qr-link-generator-for-wp'); ?>" 
                width="<?php echo esc_attr($size) . 'px'; ?>" 
            />
            <br>
            <a 
                href="<?php echo esc_url($qrcode->render($text)); ?>" 
                download="QR_Code.png" 
                style="display: <?php echo esc_attr($class); ?>;">
                <button type="button" class="button"
                    style="background-color: <?php echo esc_attr($button_background_color); ?>; color: <?php echo esc_attr($button_color); ?>;">
                    <?php echo esc_html($text_download); ?>
                </button>
            </a>
        </p>
    </div>
</div>
