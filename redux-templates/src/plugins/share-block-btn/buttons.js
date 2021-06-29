import {noop} from 'lodash'
import {Fragment} from '@wordpress/element'
import {__} from '@wordpress/i18n'
import {select, withDispatch} from '@wordpress/data'
import {compose} from '@wordpress/compose'
import {PluginBlockSettingsMenuItem} from '@wordpress/edit-post'
import { ReduxTemplatesIcon } from '../../icons';
import {ModalManager} from '../../modal-manager'
import FeedbackDialog from '~redux-templates/modal-feedback';
import sortBy from 'lodash/sortBy';
import map from 'lodash/map';
import {getWithExpiry} from '../../stores/helper';

/**
 * Based on: https://github.com/WordPress/gutenberg/blob/master/packages/editor/src/components/convert-to-group-buttons/convert-button.js
 */


/**
 * Internal dependencies
 */

const options = sortBy(getWithExpiry('page_categories_list'), 'label');
const schema = {
    type: 'object',
    properties: {
        title: {
            type: 'string',
            title: 'Block Title'
        },
        category: {
            type: 'string',
            title: 'Category',
            enum: map(options, 'value'),
            enumNames: map(options, 'label')
        },
        description: {
            type: 'string',
            title: 'Description'
        }
    }
}
const uiSchema = {
    title: {
        classNames: 'fixed-control'
    },
    category: {
        classNames: 'fixed-control'
    },
    description: {
        'ui:widget': 'textarea',
    }
};

export function ShareBlockButton({clientIds})
{
    // Only supported by WP >= 5.3.
    if (!clientIds) {
        return null
    }

    const onShareBlock = () => {
        const data = {
            postID: select('core/editor').getCurrentPostId(),
            editor_blocks: select('core/block-editor').getBlocksByClientId(clientIds),
            type: 'block'
        };
        ModalManager.openFeedback(
            <FeedbackDialog
                title={__('Redux Shares', redux_templates.i18n)}
                width={700}
                description={__('Share this design', redux_templates.i18n)}
                schema={schema}
                uiSchema={uiSchema}
                data={data}
                headerImage={<i className="fas fa-share header-icon"></i>}
                endpoint='share'
                onSuccess={data => window.open(data.data.url, '_blank')}
                buttonLabel={__('Submit Template', redux_templates.i18n)}
            />
        )
    }

    return (
        <Fragment>
            <PluginBlockSettingsMenuItem
                icon={ReduxTemplatesIcon}
                label={__('Share Block', redux_templates.i18n)}
                onClick={onShareBlock}
            />
            {/*<PluginBlockSettingsMenuItem*/}
            {/*    icon={ReduxTemplatesIcon}*/}
            {/*    label={__('Export as Reusable Block', redux_templates.i18n)}*/}
            {/*    onClick={onExportBlock}*/}
            {/*/>*/}
        </Fragment>
    )
}

export default compose([

    withDispatch((dispatch, {
        clientIds, onToggle = noop, blocksSelection = [],
    }) => {
        const {
            replaceBlocks,
        } = dispatch('core/block-editor')

        return {
            onExportBlock() {
                if (!blocksSelection.length) {
                    return
                }

                console.log(blocksSelection);

                let blocks = wp.data.select('core/block-editor').getBlocks();
                let fileName = 'blocks.json'

                const title = select('core/block-editor').getSelectedBlockName();
                const content = select('core/block-editor').getSelectedBlockClientId();
                // const content = post.content.raw;
                const fileContent = JSON.stringify(
                    {
                        __file: 'wp_block',
                        title,
                        content,
                    },
                    null,
                    2
                );
                console.log(fileContent);
                // const theFileName = kebabCase( title ) + '.json';
                //
                // download( theFileName, fileContent, 'application/json' );
                //
                //
                //
                // if (blocksSelection.length == 1) {
                //     fileName = blocksSelection[0].name.replace('/', '_') + '.json'
                // }
                //
                // saveData(blocksSelection, fileName, 'json');

                onToggle()
            },
        }
    }),
])(ShareBlockButton)
