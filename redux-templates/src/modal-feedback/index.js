/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n'
import { ModalManager } from '~redux-templates/modal-manager';
import Form from '@rjsf/core';
import {BlockPreview} from '@wordpress/block-editor';
const {useState} = wp.element;
const {apiFetch} = wp;

function FeedbackDialog(props) {
    const {title, description, schema, uiSchema, headerImage, headerIcon, data, ignoreData, endpoint, width, buttonLabel} = props;
    const {closeModal, onSuccess} = props;

    const [loading, setLoading] = useState(false);
    const [errorMessage, setErrorMessage] = useState(null);

    const onSubmit = ({formData}) => {
        const path = `redux/v1/templates/${endpoint ? endpoint : 'feedback'}`;
        if (loading) return;
        setLoading(true);
        apiFetch({
            path,
            method: 'POST',
            data: ignoreData ? formData : {...data, ...formData}
        }).then(data => {
            setLoading(false);
            if (data.success) {
                setErrorMessage(null);
                if (onSuccess) onSuccess(data); else onCloseModal();
            } else {
                console.log('There was an error: ', data);
                setErrorMessage(__('An unexpected error occured, please try again later.', redux_templates.i18n));
            }
        }).catch(err => {
            setLoading(false);
            console.log('There was an error: ', err);
            setErrorMessage(__('An unexpected error occured, please try again later.', redux_templates.i18n));
        });
    }

    const onCloseModal = () => {
        if (closeModal) closeModal(); else ModalManager.closeFeedback();
    }

    const style = width ? {width} : null;
    const wrapperClassname = width ? 'redux-templates-modal-wrapper feedback-popup-wrapper less-margin' : 'redux-templates-modal-wrapper feedback-popup-wrapper';

    return (
        <div className="redux-templates-modal-overlay">
            <div className={wrapperClassname} style={style}>
                <div className="feedback-popup-header feedback-popup-header-contact">
                    {headerImage}
                    {headerIcon}
                    <a className="feedback-popup-close" onClick={onCloseModal}>
                        <i className='fas fa-times' />
                    </a>
                </div>
                <div className="feedback-popup-content">
                    <h3>{title}</h3>
                    {errorMessage && <p className="error-message">{errorMessage}</p>}
                    <p>{description}</p>
                    <div className="col-wrapper">
                        <Form schema={schema} uiSchema={uiSchema} onSubmit={onSubmit}>
                            <button className="feedback-popup-btn feedback-popup-rate-btn" type="submit">
                                {loading && <i className="fas fa-spinner fa-pulse"/>}
                                {buttonLabel}
                            </button>
                        </Form>
                        { data && data.editor_blocks &&
                            <div className="preview-panel">
                                <div className="redux-templates-block-preview-hover" />
                                <BlockPreview blocks={data.editor_blocks} />
                            </div>
                        }
                    </div>
                </div> {/* /.feedback-popup-content */}
            </div>
        </div>
    );
}

export default FeedbackDialog;
