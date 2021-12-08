const {__} = wp.i18n

export default function ReduxTemplatesPremiumBox(props) {

	const {toProActivateStep} = props;

	const onNextStep = () => {
		toProActivateStep();
	}

	return (
        <div className="redux-templates-modal-body">
            <div className="section-box premium-box">
                <h3>{__('Upgrade to Redux Pro', redux_templates.i18n)}</h3>

                <p>{__('Thanks for giving our library a try! Upgrade to Redux Pro to unlock even more designs and to continue using our library.', redux_templates.i18n)}</p>

                <p>
                    <a href={redux_templates.u + 'import_wizard'} className="redux-templates-upgrade-button" title="{__('Redux Pro', redux_templates.i18n)}"
                       target='_blank'>{__('Upgrade Now Just $49', redux_templates.i18n)}</a>
	                <small><em>Limited time only</em></small>
                </p>
	            <p className="subscription_key_button">
		            <button type="button" className="components-button" aria-label="I have a subscription key" onClick={() => onNextStep()}>I have a subscription key
		            </button>
	            </p>
            </div>
        </div>
    );
}
