/**
 * WordPress dependencies.
 */
const { __ } = wp.i18n;

const { parse } = wp.blocks;

const {
	select,
	subscribe
} = wp.data;

const addStyle = style => {
	let element = document.getElementById( 'redux-css-editor-styles' );

	if ( null === element ) {
		element = document.createElement( 'style' );
		element.setAttribute( 'type', 'text/css' );
		element.setAttribute( 'id', 'redux-css-editor-styles' );
		document.getElementsByTagName( 'head' )[0].appendChild( element );
	}

	if ( element.textContent === style ) {
		return null;
	}

	return element.textContent = style;
};

let style = '';

const cycleBlocks = ( blocks, reusableBlocks ) => {
	blocks.forEach( block => {
		if ( block.attributes.hasCustomCSS ) {
			if ( block.attributes.customCSS && ( null !== block.attributes.customCSS ) ) {
				style += block.attributes.customCSS + '\n';
			}
		}

		if ( 'core/block' === block.name && null !== reusableBlocks ) {
			let reBlocks = reusableBlocks.find( i => block.attributes.ref === i.id );
			if ( reBlocks ) {
				reBlocks = parse( reBlocks.content.raw );
				cycleBlocks( reBlocks, reusableBlocks );
			};
		}

		if ( undefined !== block.innerBlocks && 0 < ( block.innerBlocks ).length ) {
			cycleBlocks( block.innerBlocks, reusableBlocks );
		}
	});
};

const subscribed = subscribe( () => {
	style = '';
	const { getBlocks } = select( 'core/block-editor' ) || select( 'core/editor' );
	const blocks = getBlocks();
	const reusableBlocks = select( 'core' ).getEntityRecords( 'postType', 'wp_block' );
	cycleBlocks( blocks, reusableBlocks );
	addStyle( style );
});
