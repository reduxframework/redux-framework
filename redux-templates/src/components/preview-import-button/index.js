import {__} from '@wordpress/i18n';

const {compose} = wp.compose;
const {withDispatch, withSelect} = wp.data;
import {openSitePreviewModal} from '~redux-templates/stores/actionHelper';
import ChallengeDot from '~redux-templates/challenge/tooltip/ChallengeDot';
import './style.scss'

function PreviewImportButton(props) {
    const {data, index, pageData} = props;
    const {setImportingTemplate, tourActiveButtonGroup} = props;
    let spinner = null;
    const triggerImportTemplate = (data) => {
        if (spinner === null) {
            spinner = data.ID;
            setImportingTemplate(data);
        }
    }

    return (
        <div className="action-buttons">
            {
                pageData[index] && pageData[index]['source'] !== 'wp_block_patterns' &&
                <a className="redux-templates-button preview-button" target="_blank"
                   onClick={() => openSitePreviewModal(index, pageData)}>
                    <i className="fa fa-share"/> {__('Preview', redux_templates.i18n)}
                </a>
            }

            <a className="redux-templates-button download-button"
               onClick={() => triggerImportTemplate(data)}>
                <i className="fas fa-download"/>{__('Import', redux_templates.i18n)}
            </a>
            {tourActiveButtonGroup && tourActiveButtonGroup.ID === pageData[index].ID && <ChallengeDot step={4} /> }
        </div>
    );
}


export default compose([
    withDispatch((dispatch) => {
        const {
            setImportingTemplate
        } = dispatch('redux-templates/sectionslist');

        return {
            setImportingTemplate
        };
    }),
    withSelect((select, props) => {
        const {getTourActiveButtonGroup} = select('redux-templates/sectionslist');
        return {
            tourActiveButtonGroup: getTourActiveButtonGroup()
        };
    })
])(PreviewImportButton);
