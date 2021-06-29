/**
 * WordPress dependencies
 */
import { withDispatch, withSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { compose, ifCondition } from '@wordpress/compose';
import { download } from '../export/file';
const { Fragment } = wp.element;
import { colorizeIcon } from '~redux-templates/icons'

import { Dashicon } from '@wordpress/components';

function ExportPageContentMenuItem( { createNotice, editedPostContent } ) {
    if (!wp.plugins) return null;

    const { PluginMoreMenuItem } = wp.editPost;

    const exportFullpage = () => {
        const fileContent = JSON.stringify( {
            __file: 'core_block',
            content: editedPostContent,
        }, null, 2 );

        const fileName = 'page-template-export.json';
        download( fileName, fileContent, 'application/json' );
    }


    return (
        <Fragment>
            <PluginMoreMenuItem
                icon={ colorizeIcon( <Dashicon icon="migrate" /> ) }
                role="menuitemcheckbox"
                onClick={ exportFullpage }
            >
                { __( 'Export Page', redux_templates.i18n ) }
            </PluginMoreMenuItem>
        </Fragment>
    );
}

const ExportPageContentMenu = compose(
    withSelect( ( select ) => ( {
        editedPostContent: select( 'core/editor' ).getEditedPostAttribute(
            'content'
        ),
    } ) ),
    withDispatch( ( dispatch ) => {
        const { createNotice } = dispatch( 'core/notices' );

        return {
            createNotice,
        };
    } ),
    ifCondition( ( { editedPostContent } ) => editedPostContent.length > 0 )
)( ExportPageContentMenuItem );

if (wp.plugins) {
    const { registerPlugin } = wp.plugins;
    registerPlugin('redux-templates-export-page', {
         render: ExportPageContentMenu,
    });
}
