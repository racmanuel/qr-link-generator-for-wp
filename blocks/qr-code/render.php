<?php
/**
 * Render callback for the QR Code block.
 *
 * @link       https://racmanuel.dev
 * @since      1.0.0
 *
 * @package    Qr_Link_Generator_For_Wp
 * @subpackage Qr_Link_Generator_For_Wp/blocks/qr-code
 *
 * @var array $attributes The block attributes.
 */

use chillerlan\QRCode\QRCode;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$content     = isset( $attributes['content'] ) ? $attributes['content'] : '';
$size        = isset( $attributes['size'] ) ? intval( $attributes['size'] ) : 200;
$alignment   = isset( $attributes['alignment'] ) ? sanitize_text_field( $attributes['alignment'] ) : 'center';
$show_download = isset( $attributes['showDownload'] ) ? boolval( $attributes['showDownload'] ) : true;
$download_text = isset( $attributes['downloadText'] ) ? sanitize_text_field( $attributes['downloadText'] ) : __( 'Descargar QR Code', 'qr-link-generator-for-wp' );
$product_id  = isset( $attributes['productId'] ) ? intval( $attributes['productId'] ) : 0;
$source_type = isset( $attributes['sourceType'] ) ? sanitize_text_field( $attributes['sourceType'] ) : 'custom';

// Determine the QR content.
$qr_content = '';

if ( 'product' === $source_type && $product_id > 0 ) {
	// Get the product URL.
	if ( function_exists( 'wc_get_product' ) ) {
		$product = wc_get_product( $product_id );
		if ( $product ) {
			$qr_content = $product->get_permalink();
		}
	}
} elseif ( 'custom' === $source_type && ! empty( $content ) ) {
	$qr_content = $content;
}

// If no content, show a placeholder message.
if ( empty( $qr_content ) ) {
	?>
	<div <?php echo get_block_wrapper_attributes(); ?>>
		<p style="text-align: <?php echo esc_attr( $alignment ); ?>; color: #999;">
			<?php esc_html_e( 'QR Code block: no content provided.', 'qr-link-generator-for-wp' ); ?>
		</p>
	</div>
	<?php
	return;
}

// Generate the QR code image.
try {
	$qrcode  = new QRCode();
	$qr_data = $qrcode->render( $qr_content );
} catch ( \Exception $e ) {
	?>
	<div <?php echo get_block_wrapper_attributes(); ?>>
		<p style="text-align: <?php echo esc_attr( $alignment ); ?>; color: #999;">
			<?php esc_html_e( 'Error generating QR code.', 'qr-link-generator-for-wp' ); ?>
		</p>
	</div>
	<?php
	return;
}

// Escaped inline styles.
$wrapper_style = 'text-align: ' . esc_attr( $alignment ) . ';';
$img_style     = 'width: ' . esc_attr( $size ) . 'px; height: ' . esc_attr( $size ) . 'px;';
?>

<div <?php echo get_block_wrapper_attributes(); ?>>
	<div class="qr-link-generator-for-wp-block-content" style="<?php echo $wrapper_style; ?>">
		<img
			src="<?php echo esc_attr( $qr_data ); ?>"
			alt="<?php esc_attr_e( 'QR Code', 'qr-link-generator-for-wp' ); ?>"
			style="<?php echo $img_style; ?>"
			class="qr-link-generator-for-wp-block-image"
		/>

		<?php if ( $show_download ) : ?>
			<p>
				<a
					href="<?php echo esc_attr( $qr_data ); ?>"
					download="QR_Code.png"
					class="qr-link-generator-for-wp-download-link"
				>
					<button
						type="button"
						class="qr-link-generator-for-wp-download-button button"
					>
						<?php echo esc_html( $download_text ); ?>
					</button>
				</a>
			</p>
		<?php endif; ?>
	</div>
</div>
<?php
