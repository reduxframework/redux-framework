/**
 * WordPress dependencies.
 */
const { assign } = lodash;

const { __ } = wp.i18n;

const { hasBlockSupport } = wp.blocks;

const { PanelBody } = wp.components;

const { createHigherOrderComponent } = wp.compose;

const { InspectorControls } = wp.blockEditor || wp.editor;

const { Fragment } = wp.element;

const { addFilter, removeFilter } = wp.hooks;

/**
 * Internal dependencies.
 */
import './style.scss';

import CSSEditor from './editor.js';

import './inject-css.js';

const addAttribute = ( settings ) => {
	if ( hasBlockSupport( settings, 'customClassName', true ) ) {
		settings.attributes = assign( settings.attributes, {
			hasCustomCSS: {
				type: 'boolean',
				default: false
			},
			customCSS: {
				type: 'string',
				default: null
			}
		});
	}

	return settings;
};

const withInspectorControls = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		const hasCustomClassName = hasBlockSupport( props.name, 'customClassName', true );
		if ( hasCustomClassName && props.isSelected ) {
			return (
				<Fragment>
					<BlockEdit { ...props } />
					<InspectorControls>
						<PanelBody
							title={ __( 'Custom CSS' ) }
							icon={<i className={'fa fa'}></i>}
							initialOpen={ false }
						>
							<CSSEditor
								clientId={ props.clientId }
								setAttributes={ props.setAttributes }
								attributes={ props.attributes }
							/>
						</PanelBody>
					</InspectorControls>
				</Fragment>
			);
		}

		return <BlockEdit { ...props } />;
	};
}, 'withInspectorControl' );

// Remove block-css fields.
removeFilter( 'blocks.registerBlockType', 'themeisle-custom-css/attribute' );
removeFilter( 'editor.BlockEdit', 'themeisle-custom-css/with-inspector-controls' );

addFilter( 'blocks.registerBlockType', 'redux-custom-css/attribute', addAttribute );
addFilter( 'editor.BlockEdit', 'redux-custom-css/with-inspector-controls', withInspectorControls );

