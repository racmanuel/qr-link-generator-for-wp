/**
 * QR Code Block - Editor component
 *
 * @package Qr_Link_Generator_For_Wp
 */

import { useEffect, useRef, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	RangeControl,
	SelectControl,
	ToggleControl,
	ComboboxControl,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';

/**
 * Edit component for the QR Code block.
 *
 * @param {Object}   props               Block props.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Set attributes function.
 * @return {JSX.Element} The edit component.
 */
export default function Edit({ attributes, setAttributes }) {
	const { content, size, alignment, showDownload, downloadText, productId, sourceType } = attributes;
	const qrContainerRef = useRef(null);
	const [searchTerm, setSearchTerm] = useState('');

	// Query products if WooCommerce is active.
	const products = useSelect(
		(select) => {
			const { getEntityRecords } = select('core');
			const query = {
				per_page: 20,
				status: 'publish',
				...(searchTerm ? { search: searchTerm } : {}),
			};
			return getEntityRecords('postType', 'product', query) || [];
		},
		[searchTerm]
	);

	// Check if WooCommerce is active by trying to query products.
	const wooActive = useSelect(
		(select) => {
			try {
				const { getEntityRecords } = select('core');
				const test = getEntityRecords('postType', 'product', { per_page: 1 });
				return Array.isArray(test) || test === null; // null = loading, which means the endpoint exists.
			} catch {
				return false;
			}
		},
		[]
	);

	// Generate QR in editor using the client-side library.
	useEffect(() => {
		if (!qrContainerRef.current) {
			return;
		}

		// Clear previous QR.
		qrContainerRef.current.innerHTML = '';

		const qrValue = getQrContent();
		if (!qrValue) {
			return;
		}

		try {
			// The QRCode constructor from qrcode.min.js is available globally.
			if (typeof QRCode !== 'undefined') {
				// eslint-disable-next-line no-new
				new QRCode(qrContainerRef.current, {
					text: qrValue,
					width: Math.min(size, 300),
					height: Math.min(size, 300),
					colorDark: '#000000',
					colorLight: '#ffffff',
					correctLevel: QRCode.CorrectLevel?.H || 3,
				});
			}
		} catch (error) {
			// Silently fail - QR will show on frontend.
		}
	}, [content, size, productId, sourceType]);

	/**
	 * Get the QR content based on the source type.
	 *
	 * @return {string} The content to encode in the QR.
	 */
	function getQrContent() {
		if (sourceType === 'product' && productId > 0) {
			// In the editor, we show the text "Product: [name]" as preview.
			const selectedProduct = products?.find((p) => p.id === productId);
			return selectedProduct?.title?.raw || `#${productId}`;
		}
		return content;
	}

	/**
	 * Get the display label for the current QR content.
	 *
	 * @return {string} The display label.
	 */
	function getQrLabel() {
		if (sourceType === 'product' && productId > 0) {
			const selectedProduct = products?.find((p) => p.id === productId);
			if (selectedProduct) {
				const title = selectedProduct.title?.raw || selectedProduct.title?.rendered || '';
				return 'Product: ' + title;
			}
			return 'Product #' + productId;
		}
		return content || __('Enter a URL or text...', 'qr-link-generator-for-wp');
	}

	// Build product options for the combobox.
	const productOptions = Array.isArray(products)
		? products.map((product) => ({
				value: product.id,
				label: product.title?.raw || product.title?.rendered || `#${product.id}`,
		  }))
		: [];

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('QR Code Settings', 'qr-link-generator-for-wp')} initialOpen={true}>
					{wooActive && (
						<SelectControl
							label={__('Source Type', 'qr-link-generator-for-wp')}
							value={sourceType}
							options={[
								{
									value: 'custom',
									label: __('Custom URL / Text', 'qr-link-generator-for-wp'),
								},
								{
									value: 'product',
									label: __('WooCommerce Product', 'qr-link-generator-for-wp'),
								},
							]}
							onChange={(value) => setAttributes({ sourceType: value })}
							__nextHasNoMarginBottom
						/>
					)}

					{sourceType === 'custom' && (
						<TextControl
							label={__('URL or Text', 'qr-link-generator-for-wp')}
							value={content}
							placeholder={qrSettings?.placeholder || __('Insert your content here.', 'qr-link-generator-for-wp')}
							onChange={(value) => setAttributes({ content: value })}
							help={__('Enter any URL or text to generate a QR code.', 'qr-link-generator-for-wp')}
							__nextHasNoMarginBottom
						/>
					)}

					{sourceType === 'product' && wooActive && (
						<ComboboxControl
							label={__('Select Product', 'qr-link-generator-for-wp')}
							value={productId}
							options={productOptions}
							onChange={(value) => setAttributes({ productId: value ? Number(value) : 0 })}
							onFilterValueChange={(value) => setSearchTerm(value)}
							help={__('Search and select a WooCommerce product.', 'qr-link-generator-for-wp')}
							__nextHasNoMarginBottom
							__next40pxDefaultSize
						/>
					)}

					<RangeControl
						label={__('QR Size (px)', 'qr-link-generator-for-wp')}
						value={size}
						min={100}
						max={500}
						step={10}
						onChange={(value) => setAttributes({ size: value })}
						__nextHasNoMarginBottom
					/>

					<SelectControl
						label={__('Alignment', 'qr-link-generator-for-wp')}
						value={alignment}
						options={[
							{ value: 'left', label: __('Left', 'qr-link-generator-for-wp') },
							{ value: 'center', label: __('Center', 'qr-link-generator-for-wp') },
							{ value: 'right', label: __('Right', 'qr-link-generator-for-wp') },
						]}
						onChange={(value) => setAttributes({ alignment: value })}
						__nextHasNoMarginBottom
					/>

					<ToggleControl
						label={__('Show Download Button', 'qr-link-generator-for-wp')}
						checked={showDownload}
						onChange={(value) => setAttributes({ showDownload: value })}
						__nextHasNoMarginBottom
					/>

					{showDownload && (
						<TextControl
							label={__('Download Button Text', 'qr-link-generator-for-wp')}
							value={downloadText}
							onChange={(value) => setAttributes({ downloadText: value })}
							__nextHasNoMarginBottom
						/>
					)}
				</PanelBody>
			</InspectorControls>

			<div {...useBlockProps()}>
				<div
					className="qr-link-generator-for-wp-block-wrapper"
					style={{
						textAlign: alignment,
					}}
				>
					<div
						ref={qrContainerRef}
						className="qr-link-generator-for-wp-block-qr"
						style={{
							display: 'inline-block',
							width: Math.min(size, 300),
							height: Math.min(size, 300),
						}}
					/>

					{!getQrContent() && (
						<div className="qr-link-generator-for-wp-block-empty">
							{sourceType === 'custom'
								? __('Enter a URL or text to generate a QR code.', 'qr-link-generator-for-wp')
								: __('Select a WooCommerce product.', 'qr-link-generator-for-wp')}
						</div>
					)}

					{getQrContent() && (
						<p className="qr-link-generator-for-wp-block-label">{getQrLabel()}</p>
					)}
				</div>
			</div>
		</>
	);
}
