const { compose } = wp.compose;
const { withDispatch, withSelect } = wp.data;
const { useState } = wp.element
import SitePreviewSidebar from './SitePreviewSidebar';
import { ModalManager } from '../modal-manager'
import ImportWizard from '../import-wizard';
import './style.scss';
import { Fragment } from 'react';
import {processImportHelper} from '~redux-templates/stores/actionHelper';

function PreviewTemplate(props) {

    const { startIndex, currentPageData } = props;
    const { setImportingTemplate, importingTemplate} = props;
    const [ currentIndex, setCurrentIndex ] = useState(startIndex);
    const [ previewClass, setPreviewClass ] = useState('preview-desktop')
    const [ expandedClass, toggleExpanded ] = useState('expanded')


    const onCloseCustomizer = () => {
        ModalManager.closeCustomizer();
    }

    const onNextBlock = () => {
        if (currentIndex < currentPageData.length) setCurrentIndex(currentIndex + 1);
    }

    const onPrevBlock = () => {
        if (currentIndex > 0) setCurrentIndex(currentIndex - 1);
    }


    const importStarterBlock = () => {
        setImportingTemplate(itemData);
    }

    const processImport = () => {
        if (importingTemplate) processImportHelper();
    }


    let wrapperClassName = ['wp-full-overlay sites-preview theme-install-overlay ', previewClass, expandedClass].join(' ');
    let itemData = currentPageData[currentIndex];
    return (
        <Fragment>
            <div className={wrapperClassName} style={{display: 'block'}}>
                <SitePreviewSidebar itemData={itemData} previewClass={previewClass} expandedClass={expandedClass}
                    onNextBlock={onNextBlock} onPrevBlock={onPrevBlock}
                    onCloseCustomizer={onCloseCustomizer} onToggleExpanded={e => toggleExpanded(e)}
                    onImport={importStarterBlock}
                    onChangePreviewClass={e => setPreviewClass(e)} />
                <div className='wp-full-overlay-main'>
                    <iframe src={itemData.url} target='Preview'></iframe>
                </div>
            </div>
            { importingTemplate && <ImportWizard startImportTemplate={processImport} /> }
        </Fragment>
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
        const { getImportingTemplate } = select('redux-templates/sectionslist');
        return {
            importingTemplate: getImportingTemplate()
        };
    })
])(PreviewTemplate);
