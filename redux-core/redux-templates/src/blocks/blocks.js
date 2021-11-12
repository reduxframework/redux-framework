import {__} from '@wordpress/i18n';

const { registerBlockType } = wp.blocks;
import * as importBlock from './import';
import * as libraryBlock from './library';

export function registerBlocks() {

    registerBlockType( `redux/${ libraryBlock.name }`, { ...libraryBlock.settings } );
	registerBlockType( `redux/${ importBlock.name }`, { ...importBlock.settings } );

}
registerBlocks();
