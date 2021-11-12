const {apiFetch} = wp;
const {useState} = wp.element;
const {compose} = wp.compose;
const {withDispatch} = wp.data;
const {Spinner} = wp.components;
const {parse} = wp.blocks;
const {__} = wp.i18n;
import {BlockPreview} from '@wordpress/block-editor';

import './style.scss'

import {Modal, ModalManager} from '../../modal-manager'
import reject from 'lodash/reject';

function SavedView(props) {
    const {insertBlocks, discardAllErrorMessages, appendErrorMessage, clearSearch} = props;
    const [savedSections, setSavedSections] = useState([]);
    const [dataLoaded, setDataLoaded] = useState(false);
    if (dataLoaded === false) {
        // Initial fetch
        apiFetch({path: 'redux/v1/templates/get_saved_blocks'}).then(response => {
            if (response.success) {
                setSavedSections(response.data);
            } else {
                appendErrorMessage(response.data.error);
            }
            setDataLoaded(true);
        }).catch(error => {
            appendErrorMessage(error.code + ' : ' + error.message);
            setDataLoaded(true);
        });
    }

    // To display into columns, map data into column-friendly data
    const mapToColumnData = (data, n = 4, balanced = true) => {
        let out = [], i;

        for (i = 0; i < n; i++) out[i] = [];
        data.forEach((section, i) => {
            out[i % n].push(section);
        });
        return out;
    }

    // saved block import is special
    const importSections = (rawData) => {
        let pageData = parse(rawData);
        insertBlocks(pageData);
        ModalManager.close(); //close modal
    }

    const deleteSavedSection = (event, sectionID) => {
        event.stopPropagation();
        discardAllErrorMessages();
        const options = {
            method: 'POST',
            path: 'redux/v1/templates/delete_saved_block/?block_id=' + sectionID,
        }
        apiFetch(options).then(response => {
            if (response.success) {
                // on successful remove, we will update the blocks as well.
                setSavedSections(reject(savedSections, {'ID': sectionID}));
            } else {
                appendErrorMessage(response.data.error);
            }
        }).catch(error => {
            appendErrorMessage(error.code + ' : ' + error.message);
        });
    }
    if (dataLoaded === true)
        return (
            <div className="redux-templates-two-sections__grid">
                {
                    (savedSections && savedSections.length > 0) ?
                        mapToColumnData(savedSections).map((column, key) => {
                            let sections = column.map((section, i) => {
                                let blocks = parse(section.post_content);
                                return (
                                    <div className="redux-templates-two-section" key={i}
                                        onClick={() => importSections(section.post_content)}>

                                        <div className="preview-image-wrapper">
                                            <BlockPreview blocks={blocks} />
                                        </div>
                                        <div className="saved-section-title">
                                            {section.post_title}
                                        </div>
                                        <div className="redux-templates-two-section-remove"
                                            onClick={e => deleteSavedSection(e, section.ID)}>
                                            <i className="fas fa-trash"></i>
                                        </div>
                                    </div>
                                );
                            })

                            return (
                                <div className="redux-templates-two-sections__grid__column" key={key}
                                    style={{width: '25%', flexBasis: '25%'}}>
                                    {sections}
                                </div>
                            );
                        })
                        :
                        <div className="no-section">
                            Nothing here yet, make a reusuable block first.
                        </div>
                }
            </div>
        );
    else
        return (
            <div>
                <div style={{ height: '600px' }}>
                    <div className="redux-templates-modal-loader">
                        <Spinner />
                    </div>
                </div>
            </div>
        );
}

export default compose([
    withDispatch((dispatch) => {
        const {
            insertBlocks
        } = dispatch('core/block-editor');

        const {
            appendErrorMessage,
            discardAllErrorMessages
        } = dispatch('redux-templates/sectionslist');

        return {
            insertBlocks,
            appendErrorMessage,
            discardAllErrorMessages
        };
    })
])(SavedView);
