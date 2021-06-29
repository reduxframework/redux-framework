/**
 * Internal dependencies
 */
import edit from './components/edit';
import icon from './icon';
import transforms from './transforms';
import { colorizeIcon } from '../../icons';

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;

/**
 * Block constants
 */
const name = 'import';

const category = 'common';
const schema = {
	file: {
		type: 'object',
	},
};

const title = __( 'Template Import', redux_templates.i18n );

const keywords = [
    __( 'import', redux_templates.i18n ),
    __( 'download', redux_templates.i18n ),
    __( 'migrate', redux_templates.i18n ),
];



const settings = {
	title: title,
    description: __( 'Import blocks exported using Redux plugin.', redux_templates.i18n ),

	category: category,
	keywords: keywords,

    attributes: schema,

    supports: {
        align: true,
        alignWide: false,
        alignFull: false,
        customClassName: false,
        className: false,
        html: false,
    },

    transforms: transforms,
    edit: edit,
    save() {
        return null;
    },
};

export { name, title, category, icon, settings };
