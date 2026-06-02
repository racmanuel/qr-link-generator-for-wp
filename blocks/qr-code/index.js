/**
 * QR Code Block - Entry point
 *
 * @package Qr_Link_Generator_For_Wp
 */

import { registerBlockType } from '@wordpress/blocks';
import { blockDefault } from '@wordpress/icons';
import './style.scss';
import Edit from './edit';
import metadata from './block.json';

registerBlockType(metadata, {
	icon: blockDefault,
	edit: Edit,
	save: () => null,
});
