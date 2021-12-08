const { parse, createBlock } = wp.blocks;
const { apiFetch } = wp;
const { dispatch, select } = wp.data;
const { getBlockOrder } = select( 'core/block-editor' );
const { getBlockTypes } = select('core/blocks');
const { savePost, editPost } = dispatch('core/editor');
const { insertBlocks, removeBlocks, multiSelect } = dispatch('core/block-editor');
const { createSuccessNotice, createErrorNotice, createNotice, removeNotice } = dispatch('core/notices');
import { __ } from '@wordpress/i18n'
import { ModalManager } from '~redux-templates/modal-manager';
import PreviewModal from '../modal-preview';
import FeedbackDialog from '~redux-templates/modal-feedback';


// create Block to import template
export const handleBlock = (data, installedDependencies) => {
    let block_data = null;
    if ('template' in data) {
        block_data = parse(data.template);
    } else if ('attributes' in data) {
        if (!('innerBlocks' in data)) {
            data.innerBlocks = [];
        }
        if (!('name' in data)) {
            errorCallback('Template malformed, `name` for block not specified.');
        }
        // This kind of plugins are not ready to accept before reloading, thus, we save it into localStorage and just reload for now.
        if (installedDependencies === true) {
            window.redux_templates_tempdata = [...window.redux_templates_tempdata, data];
            return null;
        } else {
            block_data = createBlock(data.name, data.attributes, data.innerBlocks)
        }
    } else {
        errorCallback('Template error. Please try again.');
    }
    return block_data;
}

export const processImportHelper = () => {
    const { setImportingTemplate, discardAllErrorMessages, clearSearch } = dispatch('redux-templates/sectionslist');
    const type = select('redux-templates/sectionslist').getActiveItemType() === 'section' ? 'sections' : 'pages';
    const data = select('redux-templates/sectionslist').getImportingTemplate();
    const installedDependencies = select('redux-templates/sectionslist').getInstalledDependencies();
    const isImportToAppend = select('redux-templates/sectionslist').getImportToAppend();

    if (type === 'pages') {
    	editPost({'template': 'redux-templates_full_width'});
    } else {
	    if ( '' === select( 'core/editor' ).getEditedPostAttribute( 'template' ) ) {
		    editPost({'template': 'redux-templates_contained'});
	    }
    }

    discardAllErrorMessages();
    let the_url = 'redux/v1/templates/template?type=' + type + '&id=' + data.id + '&uid=' + window.userSettings.uid;
    if ('source' in data) {
        the_url += '&source=' + data.source;
    }

    const options = {
        method: 'GET',
        path: the_url,
        headers: { 'Content-Type': 'application/json', 'Registered-Blocks': installedBlocksTypes() }
    };

    if (dispatch('core/edit-post') && select('core/edit-post').getEditorMode() === 'text') {
        const { switchEditorMode } = dispatch('core/edit-post');
        switchEditorMode()
    }
    window.redux_templates_tempdata = [];

    apiFetch(options).then(response => {
        // First, let's give user feedback.
        displayNotice(response.data, {type: 'snackbar'});
        
        if (isImportToAppend === false) {
            const rootBlocksClientIds = getBlockOrder();
            multiSelect(
                rootBlocksClientIds[0],
                rootBlocksClientIds[rootBlocksClientIds.length - 1]
            );
            removeBlocks( rootBlocksClientIds );
        }

        if (response.success && response.data) {
            let responseBlockData = response.data;

            // Important: Update left count from the response in case of no Redux PRO
            if (redux_templates.mokama !== '1' && isNaN(responseBlockData.left) === false)
                redux_templates.left = responseBlockData.left;

            let handledData = [];
            if (responseBlockData.hasOwnProperty('template') || responseBlockData.hasOwnProperty('attributes'))
                handledData = handleBlock(responseBlockData, installedDependencies);
            else
                handledData = Object.keys(responseBlockData).filter(key => key !== 'cache')
                    .map(key => handleBlock(responseBlockData[key], installedDependencies));

            localStorage.setItem('importing_data', JSON.stringify(data));
            localStorage.setItem('block_data', JSON.stringify(redux_templates_tempdata));
            localStorage.setItem('is_appending', isImportToAppend);

            insertBlocks(handledData);
            createSuccessNotice('Template inserted', { type: 'snackbar' });

            if (installedDependencies === true)
                savePost()
                    .then(() => window.location.reload())
                    .catch(() => createErrorNotice('Error while saving the post', { type: 'snackbar' }));
            else {
                ModalManager.close();
                ModalManager.closeCustomizer();
                setImportingTemplate(null);
            }
            afterImportHandling(data, handledData);

        } else {
            if (response.success === false)
                errorCallback(response.data.message);
            else
                errorCallback(response.data.error);
        }
    }).catch(error => {
        errorCallback(error.code + ' : ' + error.message);
    });
}

const detectInvalidBlocks = (handleBlock) => {
    if (Array.isArray(handleBlock) === true) return handleBlock.filter(block => block.isValid === false);
    return handleBlock && handleBlock.isValid===false ? [handleBlock] : null;
}

// used for displaying notice from response data
const displayNotice = (data, options) => {
    if (data && data.message) {
        const noticeType = data.messageType || 'info';
        createNotice(noticeType, data.message, options)
    }
}

// show notice or feedback modal dialog based on imported block valid status
export const afterImportHandling = (data, handledBlock) => {
    const invalidBlocks = detectInvalidBlocks(handledBlock);
    // get the description from the invalid blocks
    let description = '';
    if (invalidBlocks && invalidBlocks.length < 1)
        description = invalidBlocks.map(block => {
            if (block.validationIssues && Array.isArray(block.validationIssues))
                return block.validationIssues.map(error => {
                    return sprintf(...error.args)
                }).join('\n');
            else
                return null;
        }).join('\n');

    // Prepare Form schema object
    const schema = {
        type: 'object',
        properties: {
            theme_plugins: {
                type: 'boolean',
                title: __('Send theme and plugins', redux_templates.i18n),
                default: true
            },
            send_page_content: {
                type: 'boolean',
                title: __('Send page content', redux_templates.i18n),
                default: true
            },
            template_id: {
                type: 'string',
                default: data.hash,
                title: __('Template ID', redux_templates.i18n)
            },
            description: {
                type: 'string',
                default: description,
                title: __('Description', redux_templates.i18n)
            },

        }
    }
    const uiSchema = {
        description: {
            'ui:widget': 'textarea',
        },
        template_id: {
            'ui:disabled': true,
            classNames: 'fixed-control'
        }
    };

    const feedbackData = {
        content: handledBlock
    };
    if (invalidBlocks && invalidBlocks.length > 0) { // in case there
        createNotice('error', 'Please let us know if there was an issue importing this Redux template.', {
            isDismissible: true,
            id: 'redux-templatesimportfeedback',
            actions: [
                {
                    onClick: () => ModalManager.openFeedback(<FeedbackDialog
                        title={__('Thank you for reporting an issue.', redux_templates.i18n)}
                        description={__('We want to make Redux perfect. Please send whatever you are comfortable sending, and we will do our best to resolve the problem.', redux_templates.i18n)}
                        schema={schema}
                        uiSchema={uiSchema}
                        data={feedbackData}
                        ignoreData={true}
                        headerImage={<img className="header-background" src={`${redux_templates.plugin}assets/img/popup-contact.png` } />}
                        buttonLabel={__('Submit Feedback', redux_templates.i18n)}
                        />),
                    label: 'Report an Issue',
                    isPrimary: true,
                }
            ],
        });
    }
}

// reload library button handler
export const reloadLibrary = () => {
    const { setLoading, setLibrary } = dispatch('redux-templates/sectionslist');
    setLoading(true);
    apiFetch({
        path: 'redux/v1/templates/library?no_cache=1',
        method: 'POST',
        data: {
            'registered_blocks': installedBlocksTypes(),
        }
    }).then((newLibrary) => {
        setLoading(false);
        setLibrary(newLibrary.data);
    }).catch((error) => {
        errorCallback(error);
    });
}


export const installedBlocks = () => {
    let installed_blocks = getBlockTypes();
    return Object.keys(installed_blocks).map(key => {
        return installed_blocks[key]['name'];
    })
}
export const installedBlocksTypes = () => {
    let installed_blocks = getBlockTypes();

    let names = Object.keys(installed_blocks).map(key => {
        if (!installed_blocks[key]['name'].includes('core')) {
            return installed_blocks[key]['name'].split('/')[0];
        }
    })
    let unique = [...new Set(names)];
    var filtered = unique.filter(function (el) {
        return el;
    });

    return filtered
}

export const openSitePreviewModal = (index, pageData) => {
    ModalManager.openCustomizer(
        <PreviewModal startIndex={index} currentPageData={pageData} />
    )
}

const errorCallback = (errorMessage) => {
    const { appendErrorMessage, setImportingTemplate, setActivateDialogDisplay } = dispatch('redux-templates/sectionslist');
    if (errorMessage === 'Please activate Redux') {
        setActivateDialogDisplay(true);
        redux_templates.left = 0;
    } else {
        appendErrorMessage(errorMessage);
        setImportingTemplate(null);
    }
}
