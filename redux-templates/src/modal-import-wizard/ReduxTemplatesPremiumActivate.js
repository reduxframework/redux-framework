// noinspection JSAnnotator

const {__, sprintf} = wp.i18n;
const {useState} = wp.element;
const {apiFetch} = wp;

export default function ReduxTemplatesPremiumActivate(props) {

	const [installing, setInstalling] = useState(null);
	const [status, setStatus] = useState(__('Enter your API key to activate and install your copy of Redux Pro.', redux_templates.i18n));
	const [statusType, setStatusType] = useState('');
	const [proKey, setProKey] = useState( atob( redux_templates.key ) );
	const {toPluginStep} = props;

	const installReduxPlugin = async () => {
		if ( ! redux_templates.supported_plugins['redux-framework'].plugin ) {
			setStatus(__('Installing the redux-framework plugin.', redux_templates.i18n));
			await apiFetch({
				path: 'redux/v1/templates/plugin-install?slug=redux-framework',
			}).then(res => {
				if (res.success) {
					installReduxPro();
				} else {
					setStatus(__('Install of Redux Pro failed, please try again.', redux_templates.i18n));
					setInstalling(null);
				}
			})
			.catch(res => {
				setStatus(__('There seems to be an API issue. Please contact Redux support or try again.', redux_templates.i18n));
				setInstalling(null);
			});
		} else {
			installReduxPro();
		}
	};

	const installReduxPro = async () => {
		if ( ! redux_templates.supported_plugins['redux-pro'].version ) {
			setStatus(__('Installing Redux Pro.', redux_templates.i18n));
			await apiFetch({
				path: 'redux/v1/templates/plugin-install?slug=redux-pro&redux_pro=1',
			}).then(res => {
				if (res.success) {
					setStatus(__('Redux Pro successfully installed!', redux_templates.i18n));
					redux_templates.mokama = true;
					delete redux_templates.left;
					toPluginStep();
				} else {
					setStatus(__('Install of Redux Pro failed, please try again.', redux_templates.i18n));
				}
			})
			.catch(res => {
				setStatus(__('There seems to be an API issue. Please contact Redux support or try again.', redux_templates.i18n));
				setInstalling(null);
			});
		} else {
			redux_templates.mokama = true;
			delete redux_templates.left;
			toPluginStep();
		}
	};

	const activateKey = async () => {
		setInstalling(true);
		setStatus(__('Validating license key.', redux_templates.i18n));
		await apiFetch({
			path: 'redux/v1/templates/license?key=' + proKey,
		}).then(res => {
			if (res.success) {
				setStatus(__('API key activated, installing Redux Pro.', redux_templates.i18n));
				installReduxPlugin();
			} else {
				if ( res.msg ) {
					setStatus(res.msg);
				} else {
					setStatus(__('License key failed to activate. Please try again.', redux_templates.i18n));
				}
				setInstalling(null);
			}
		}).catch(res => {
			setStatus(__('There seems to be an API issue. Please contact Redux support or try again.', redux_templates.i18n));
			setInstalling(null);
		});
	};

	const updateProKey = (e) => {
		setProKey(e.target.value);
	}

	return (
		<div className="redux-templates-modal-body">
			<div className="section-box premium-box">
				<h3>{__('Activate Redux Pro', redux_templates.i18n)}</h3>

				<div className={statusType}>{status}</div>

				<p>
					<input type="text" placeholder={__('Enter your subscription key', redux_templates.i18n)} value={proKey} onChange={updateProKey} disabled={installing !== null} />
				</p>
				<p className="subscription_key">
					<button className="redux-pro-activate-button" aria-label="Activate my key" disabled={installing !== null}
					        onClick={() => activateKey()}>
						{installing !== null && <i className="fas fa-spinner fa-pulse"/>}
						<span>{__('Activate & Install Pro', redux_templates.i18n)}</span>
					</button>
				</p>
				<p style={{fontSize:'1.1em', paddingTop:'10px'}}><small><em dangerouslySetInnerHTML={{__html: redux_templates.tos.replace(__('Register', redux_templates.i18n), __('Activate', redux_templates.i18n))}} /> <br /><span dangerouslySetInnerHTML={{__html: sprintf( __( 'Visit the <a href="%s" target="_blank">account dashboard</a> to recover your subscription key.', redux_templates.i18n ), 'https://redux.io/account/?utm_source=plugin-installation&utm_campaign=import_wizard&utm_medium=appsero&utm_content=activate_pro' )}} /></small>
				</p>
			</div>
		</div>
	);
}
