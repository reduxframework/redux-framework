/**
 * BLOCK: Design Library
 */
/**
 * External dependencies
 */
import { ReduxTemplatesIcon } from '~redux-templates/icons'

/**
 * Internal dependencies
 */
import edit from './edit'
import InsertLibraryButton from './insert-library-button'
const { registerBlockType } = wp.blocks;

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n'
import domReady from '@wordpress/dom-ready'
import { render } from '@wordpress/element'
import { ReduxTemplatesIconColor } from '../../icons';



const name = 'library';
const icon = InsertLibraryButton

const category = 'common';
const schema = {}

const title = __( 'Template Library', redux_templates.i18n );
const description = __( 'Choose a section, template, or template kit from the Redux Template Library.', redux_templates.i18n );

const keywords = [
	__( 'Template Library', redux_templates.i18n ),
	__( 'Design Library', redux_templates.i18n ),
	__( 'Element Layouts', redux_templates.i18n ),
	__( 'Redux', redux_templates.i18n ),
];

const blockAttributes = {
	file: {
		type: 'object',
	},
};

const settings = {
	title: title,
	description: description,
	icon: ReduxTemplatesIconColor,
	category: 'layout',
	keywords: keywords,
	attributes: schema,
	supports: {
		customClassName: false,
		// inserter: ! disabledBlocks.includes( name ), // Hide if disabled.
	},

	example: {
		attributes: {
			// backgroundColor: '#000000',
			// opacity: 0.8,

			// padding: 30,
			// textColor: '#FFFFFF',
			// radius: 10,
			// title: __( 'I am a slide title', 'wp-presenter-pro' ),
		},
	},

	edit: edit,

	save() {
		return null;
	},
};

const renderButton = function(toolbar) {

	const buttonDiv = document.createElement( 'div' )
	toolbar.appendChild( buttonDiv )

	render( <InsertLibraryButton />, buttonDiv )
}

domReady( () => {
	let toolbar = document.querySelector( '.edit-post-header__toolbar' );
	if ( ! toolbar ) {
		toolbar = document.querySelector( '.edit-post-header__toolbar' );
	}
	if ( ! toolbar ) {
		setTimeout(function(){
			let toolbar = document.querySelector( '.edit-post-header__toolbar' );
			if ( toolbar ) {
				renderButton( toolbar );
			}
		}, 500);
		return;
	}
	renderButton(toolbar);
} )

export { name, title, category, icon, settings };
