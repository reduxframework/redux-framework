const {__} = wp.i18n;
const {useState, useEffect} = wp.element;
const {apiFetch} = wp;
import {installedBlocksTypes} from '~redux-templates/stores/actionHelper';
import {Modal, ModalManager} from '../modal-manager'
import './style.scss'

export default function FeedbackModal(props) {
    const {importedData, handledBlock, invalidBlocks} = props;
    const [description, setDescription] = useState('');
    const [loading, setLoading] = useState(false);
    const [sendingThemePlugins, setSendingThemePlugins] = useState(true);
    const [sendingContent, setSendingContent] = useState(true);
    const [errorMessage, setErrorMessage] = useState('');
    const [panelClassname, setPanelClassname] = useState('panel')

    const submitFeedback = () => {
        if (loading) return;
        setLoading(true);

        let data = {
            description,
            'theme_plugins': sendingThemePlugins,
            'template_id': importedData.hash
        };
        if (sendingContent) {
            data.content = handledBlock;
        }
        apiFetch({
            path: 'redux-templates/v1/feedback/',
            method: 'POST',
            headers: {'Registered-Blocks': installedBlocksTypes()},
            data
        }).then(data => {
            setLoading(false);
            if (data.success) {
                setPanelClassname('panel fade')
            } else {
                setErrorMessage(__('An Error occured', redux_templates.i18n));
            }
        }).catch(err => {
            setLoading(false);
            setErrorMessage(__('There was an error: ', redux_templates.i18n) + err.message);
        });
    }

    const onCloseWizard = () => {
        ModalManager.close();
    }

    useEffect(() => {
        if (invalidBlocks && invalidBlocks.length > 0) {
            setDescription(
                invalidBlocks.map(block => {
                    if (block.validationIssues && Array.isArray(block.validationIssues))
                        return block.validationIssues.map(error => {
                            return sprintf(...error.args)
                        }).join('\n');
                    else
                        return null;
                }).join('\n')
            );
        }
    }, [invalidBlocks]);

    return (
        <Modal compactMode={true}>
            <div className="redux-templates-feedback-modal-wrapper">
                <div className="redux-templates-modal-header">
                    <h3>{__('Feedback Wizard', redux_templates.i18n)}</h3>
                    <button className="redux-templates-modal-close" onClick={onCloseWizard}>
                        <i className={'fas fa-times'}/>
                    </button>
                </div>
                <div className="redux-templates-feedback">
                    {
                        errorMessage.length > 0 &&
                        <div className="error-panel">
                            {errorMessage}
                        </div>
                    }
                    <h4>{__('Thank you for reporting an issue.', redux_templates.i18n)}</h4>
                    <div className={panelClassname}>
                        <p>{__('We want to make Redux perfect. Please send whatever you are comfortable sending, and we will do our best to resolve the problem.', redux_templates.i18n)}</p>
                        <div className="field">
                            <input type="checkbox" id="theme_plugins" checked={sendingThemePlugins} onChange={() => setSendingThemePlugins(!sendingThemePlugins)} />
                            <label htmlFor="theme_plugins">Send theme and plugins</label>
                        </div>
                        <div className="field">
                            <input type="checkbox" id="content" checked={sendingContent} onChange={() => setSendingContent(!sendingContent)} />
                            <label htmlFor="content">Send page content</label>
                        </div>
                        <div className="field">
                            <label htmlFor="template_id">Template ID</label>
                            <input type="input" id="template_id" disabled="disabled" value={importedData.hash} />
                        </div>
                        <div className="field top">
                            <label>Description</label>
                            <textarea value={description} onChange={(e) => setDescription(e.target.value)} />
                        </div>
                        <button className="button button-primary" onClick={submitFeedback}>
                            {loading ? <i className="fas fa-spinner fa-pulse"/> :
                                <i className="fas fa-share"></i>} Submit Feedback
                        </button>
                    </div>
                </div>
            </div>
        </Modal>
    );
}
