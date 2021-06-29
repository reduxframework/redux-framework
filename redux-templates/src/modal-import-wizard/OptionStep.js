import {pluginInfo} from '~redux-templates/stores/dependencyHelper';
import {Tooltip} from '@wordpress/components';

const {apiFetch} = wp;
const {compose} = wp.compose;
const {withDispatch} = wp.data;
const {Fragment, useState} = wp.element;
const {__} = wp.i18n;

function OptionStep(props) {

    const {setImportToAppend, toNextStep, onCloseWizard} = props;

    const onNextStep = (isToAppend) => {
        setImportToAppend(isToAppend);
        toNextStep();
    }

    return (

        <Fragment>
            <div className="redux-templates-modal-body">
                <h5>{__('Append or Replace', redux_templates.i18n)}</h5>
                <p>{__('You have existing content on this page. How would you like to handle the import of this page template?', redux_templates.i18n)}</p>
	            <div style={{textAlign:'center', marginTop: '30px'}}>
		            {/*<Tooltip text={__('This template will be added to the bottom of the existing content.', redux_templates.i18n)} position="bottom center">*/}
			            <button className="button button-primary" onClick={() => onNextStep(true)} style={{marginRight: '10px'}}>
				            {__('Append to Content', redux_templates.i18n)}
			            </button>
		            {/*</Tooltip>*/}
		            {/*<Tooltip text={__('All the existing content will be replaced with this new template.', redux_templates.i18n)} position="top right">*/}
			            <button className="button button-primary" onClick={() => onNextStep(false)}>
				            {__('Replace all Content', redux_templates.i18n)}
			            </button>
		            {/*</Tooltip>*/}
	            </div>
            </div>
            <div className="redux-templates-modal-footer">
                <button className="button button-secondary" onClick={onCloseWizard}>
                    {__('Cancel', redux_templates.i18n)}
                </button>
            </div>
        </Fragment>
    );
}


export default compose([
    withDispatch((dispatch) => {
        const {
            setImportToAppend
        } = dispatch('redux-templates/sectionslist');
        return {
            setImportToAppend
        };
    })
])(OptionStep);
