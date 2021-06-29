/**
 * External dependencies
 */
import React, {Component} from 'react';

import SVGRedux from '../../assets/img/icon.svg'
import SVGAcfBlocks from './images/acf-blocks.svg'
import SVGAtomicBlocks from './images/atomic-blocks.svg'
import SVGAdvancedCustomFields from './images/advanced-custom-fields.svg'
import SVGAdvancedGutenbergBlocks from './images/advanced-gutenberg-blocks.svg'
import SVGBlockOptions from './images/block-options.svg'
import SVGBlockSlider from './images/block-slider.svg'
import SVGCoblocks from './images/coblocks.svg'
import SVGCreativeBlocks from './images/creative-blocks.svg'
import SVGEditorPlus from './images/editorplus.svg'
import SVGElegantBlocks from './images/elegant-blocks.svg'
import SVGEnhancedBlocks from './images/enhanced-blocks.svg'
import SVGEssentialBlocks from './images/essential-blocks.svg'
import SVGFormsGutenberg from './images/forms-gutenberg.svg'
import SVGGetwid from './images/getwid.svg'
import SVGGhostkit from './images/ghostkit.svg'
import SVGGuteblock from './images/guteblock.svg'
// import SVGGutenbergBlock from './images/gutenberg-blocks.png'
import SVGGutentor from './images/gutentor.svg'
import SVGKadenceBlocks from './images/kadence-blocks.svg'
import SVGKiokenBlocks from './images/kioken-blocks.svg'
import SVGOtterBlocks from './images/otter-blocks.svg'
import SVGQodeblock from './images/qodeblock.svg'
import SVGQubely from './images/qubely.svg'
import SVGSnowMonkeyBlocks from './images/snow-monkey-blocks.svg'
import SVGStackableUltimateGutenbergBlocks from './images/stackable-ultimate-gutenberg-blocks.svg'
import SVGUltimateAddonsForGutenberg from './images/ultimate-addons-for-gutenberg.svg'
import SVGUltimateBlocks from './images/ultimate-blocks.svg'
import SVGUltimatePost from './images/ultimate-post.svg'
import SVGWordPress from './images/wordpress.svg'

// export const gutentor = () => {
// 	return <SVGGutentorIcon width="20" height="20"/>
// }


export const redux = () => { return <SVGRedux width="20" height="20"/> }
export const acfblocks = () => { return <SVGAcfBlocks width="20" height="20"/> }
export const atomicblocks = () => { return <SVGAtomicBlocks width="20" height="20"/> }
export const advancedcustomfields = () => { return <SVGAdvancedCustomFields width="20" height="20"/> }
export const advancedgutenbergblocks = () => { return <SVGAdvancedGutenbergBlocks width="20" height="20"/> }
export const blockoptions = () => { return <SVGBlockOptions width="20" height="20"/> }
export const blockslider = () => { return <SVGBlockSlider width="20" height="20"/> }
export const coblocks = () => { return <SVGCoblocks width="20" height="20"/> }
export const creativeblocks = () => { return <SVGCreativeBlocks width="20" height="20"/> }
export const editorplus = () => { return <SVGEditorPlus width="20" height="20"/> }
export const elegantblocks = () => { return <SVGElegantBlocks width="20" height="20"/> }
export const enhancedblocks = () => { return <SVGEnhancedBlocks width="20" height="20"/> }
export const essentialblocks = () => { return <SVGEssentialBlocks width="20" height="20"/> }
export const formsgutenberg = () => { return <SVGFormsGutenberg width="20" height="20"/> }
export const getwid = () => { return <SVGGetwid width="20" height="20"/> }
export const ghostkit = () => { return <SVGGhostkit width="20" height="20"/> }
export const guteblock = () => { return <SVGGuteblock width="20" height="20"/> }
export const gutenbergblock = () => { return <SVGGutenbergBlock width="20" height="20"/> }
export const gutentor = () => { return <SVGGutentor width="20" height="20"/> }
export const kadenceblocks = () => { return <SVGKadenceBlocks width="20" height="20"/> }
export const kiokenblocks = () => { return <SVGKiokenBlocks width="20" height="20"/> }
export const otterblocks = () => { return <SVGOtterBlocks width="20" height="20"/> }
export const qodeblock = () => { return <SVGQodeblock width="20" height="20"/> }
export const qubely = () => { return <SVGQubely width="20" height="20"/> }
export const snowmonkeyblocks = () => { return <SVGSnowMonkeyBlocks width="20" height="20"/> }
export const stackableultimategutenbergblocks = () => { return <SVGStackableUltimateGutenbergBlocks width="20" height="20"/> }
export const ultimateaddonsforgutenberg = () => { return <SVGUltimateAddonsForGutenberg width="20" height="20"/> }
export const ultimateblocks = () => { return <SVGUltimateBlocks width="20" height="20"/> }
export const ultimatepost = () => { return <SVGUltimatePost width="20" height="20"/> }
export const wordpress = () => { return <SVGWordPress width="20" height="20"/> }

import SVGReduxTemplatesIcon from '../../assets/img/icon.svg'
import SVGReduxTemplatesColorIcon from '../../assets/img/icon-color.svg'
//
//
// export const reqSvgs = require.context ( './images/third-party', true, /\.svg$/ )
//
// export const reqSvgsKeys = reqSvgs.keys()
//
// const iconLoader = (path) => import(path);
//
// export const icons = {
// 	'redux': iconLoader('../../assets/img/icon.svg'),
// 	'forms-gutenberg': iconLoader('./images/forms-gutenberg.svg')
// }
//
// export const svgs = reqSvgs
// 	.keys ()
// 	.reduce ( ( images, path ) => {
// 		images[path.replace('./', '').replace('.svg', '')] = reqSvgs ( path )
// 		return images
// 	}, {} )
//
// function importAll(r) {
// 	let images = {};
// 	r.keys().map((item, index) => { images[item.replace('./', '').replace('.svg', '')] = r(item); });
// 	return images;
// }
// export const images = importAll(require.context( './images/third-party', false, /\.(svg)$/));



/**
 * WordPress dependencies
 */
import {cloneElement, render} from '@wordpress/element'
import domReady from '@wordpress/dom-ready'
import {updateCategory} from '@wordpress/blocks'

export const colorizeIcon = SvgIcon => {
	return cloneElement(SvgIcon, {
		fill: 'url(#redux-gradient)',
		className: 'redux-icon-gradient',
	})
}

export const thirdPartyIcon = (icon) => {
	if (icon) {
		return <icon width="20" height="20"/>
	}
}

// Add an icon to our block category.
if (typeof window.wp.blocks !== 'undefined' && typeof window.wp.blocks.updateCategory !== 'undefined') {
	updateCategory(redux_templates.i18n, {
		icon: colorizeIcon(<SVGReduxTemplatesIcon className="components-panel__icon" width="20" height="20"/>),
	})
}

// Add our SVG gradient placeholder definition that we'll reuse.
domReady(() => {
	const redux_templatesGradient = document.createElement('DIV')
	document.querySelector('body').appendChild(redux_templatesGradient)
	render(
		<svg
			xmlns="http://www.w3.org/2000/svg"
			className="redux-gradient"
			height="0"
			width="0"
			style={{opacity: 0}}
		>
			<defs>
				<linearGradient id="redux-gradient">
					<stop offset="0%" stopColor="#8c33da" stopOpacity="1"/>
					<stop offset="100%" stopColor="#f34957" stopOpacity="1"/>
				</linearGradient>
			</defs>
		</svg>,
		redux_templatesGradient
	)
})

export const ReduxTemplatesIcon = () => {
	return <SVGReduxTemplatesIcon width="20" height="20"/>
}

export const ReduxTemplatesIconColor = () => {
	return <SVGReduxTemplatesColorIcon width="20" height="20"/>
}

export const ReduxTemplatesIconColorize = () => {
	return colorizeIcon(<SVGReduxTemplatesIcon width="20" height="20"/>)
}
export const core = () => {
	return <SVGWordPress width="20" height="20"/>
}
//
// export const AdvancedGutenbergBlocks = () => {
// 	return <SVGAdvancedGutenbergBlocksIcon width="20" height="20"/>
// }
// export const advancedgutenbergblocks = () => <AdvancedGutenbergBlocks/>
//
// export const AdvancedGutenberg = () => {
// 	return <SVGAdvancedGutenbergIcon width="20" height="20"/>
// }
// export const advancedgutenbergIcon = () => <AdvancedGutenberg/>
//
// export const AtomicBlocks = () => {
// 	return <SVGAtomicBlocksIcon width="20" height="20"/>
// }
// export const atomicblocks = () => <AtomicBlocks/>
//
// export const CoBlocks = () => {
// 	return <SVGCoBlocksIcon width="20" height="20"/>
// }
// export const Coblocks = () => <CoBlocks/>
// export const coblocks = () => <CoBlocks/>
//
// export const Stackable = () => {
// 	return <SVGStackableIcon width="20" height="20"/>
// }
// export const stackable = () => <Stackable/>
// export const stackableultimategutenbergblocks = () => <Stackable/>
//
// export const Qubely = () => {
// 	return <SVGQubelyIcon width="20" height="20"/>
// }
// export const qubely = () => <Qubely/>
//
// export const Kioken = () => {
//     return <SVGKiokenIcon width="20" height="20"/>
// }
// export const kioken = () => <Kioken/>
// export const kiokenblocks = () => <Kioken/>
//
// export const kadenceblocks = () => {
// 	return <SVGKadenceIcon width="20" height="20"/>
// }
//
// export const CreativeBlocks = () => {
// 	return <SVGCreativeBlocksIcon width="20" height="20"/>
// }
// export const creativeblocks = () => <CreativeBlocks/>
// export const qb = () => <CreativeBlocks/>
//
// export const EssentialBlocks = () => {
// 	return <SVGEssentialBlocksIcon width="20" height="20"/>
// }
// export const essentialblocks = () => <EssentialBlocks/>
// export const eb = () => <EssentialBlocks/>
//
// export const UltimateAddonsForGutenberg = () => {
// 	return <SVGUltimateAddonsForGutenbergIcon width="20" height="20"/>
// }
// export const ultimateaddonsforgutenberg = () => <UltimateAddonsForGutenberg/>
//
//
// export const UltimateBlocks = () => {
// 	return <SVGUltimateBlocksIcon width="20" height="20"/>
// }
// export const ultimateblocks = () => <UltimateBlocks/>
//
// export const gutentor = () => {
// 	return <SVGGutentorIcon width="20" height="20"/>
// }
//
//
// export const GutenbergForms = () => {
// 	return <SVGGutenbergFormsIcon width="20" height="20"/>
// }
// export const gutenbergforms = () => <GutenbergForms/>
// export const formsgutenberg = () => <GutenbergForms/>
//
