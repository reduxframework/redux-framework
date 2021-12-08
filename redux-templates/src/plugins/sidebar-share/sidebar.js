const {__} = wp.i18n;
const {compose} = wp.compose;
const {withSelect, select} = wp.data;
const {Fragment} = wp.element;
const {PanelBody} = wp.components

import sortBy from 'lodash/sortBy';
import map from 'lodash/map';
import {ModalManager} from '../../modal-manager'
import FeedbackDialog from '~redux-templates/modal-feedback';
import {getWithExpiry} from '../../stores/helper';

const options = sortBy(getWithExpiry('section_categories_list'), 'label');
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

function Sidebar(props) {
    if (!wp.editPost) return null;
	return null; // TODO - Finish fixing this experience.
    const {PluginSidebar, PluginSidebarMoreMenuItem} = wp.editPost;
    const {getEditorBlocks} = props;
    const onShare = () => {
        const data = {
            postID: select('core/editor').getCurrentPostId(),
            editor_blocks: getEditorBlocks(),
            type: 'page'
        };
        ModalManager.openFeedback(
            <FeedbackDialog
                title={__('Redux Shares', redux_templates.i18n)}
                description={__('Share this design', redux_templates.i18n)}
                schema={schema}
                uiSchema={uiSchema}
                data={data}
                width={700}
                headerImage={<i className="fas fa-share header-icon"></i>}
                endpoint='share'
                onSuccess={data => window.open(data.data.url, '_blank')}
                buttonLabel={__('Submit Template', redux_templates.i18n)}
            />
        )
    }

    return (
        <Fragment>
            <PluginSidebarMoreMenuItem target="redux-templates-share">
                {__('Redux Template', redux_templates.i18n)}
            </PluginSidebarMoreMenuItem>
            <PluginSidebar name="redux-templates-share" title={__('Redux Shares', redux_templates.i18n)}>
                <PanelBody title={__('Share this Design', redux_templates.i18n)} initialOpen={true}>
                    <div className="d-flex justify-content-center">
                        <a className="button button-primary" onClick={onShare}>
                            <i className="fas fa-share"></i>
                            &nbsp;{__('Share this design', redux_templates.i18n)}
                        </a>
                    </div>
                </PanelBody>
            </PluginSidebar>
        </Fragment>
    );
}

export default compose([
    withSelect((select) => {
        const {getEditorBlocks} = select('core/editor');
        return {
            getEditorBlocks
        };
    })
])(Sidebar);
