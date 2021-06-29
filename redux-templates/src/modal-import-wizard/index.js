const {__} = wp.i18n;
const {compose} = wp.compose;
const {withDispatch, withSelect} = wp.data;
const {useState, useEffect} = wp.element;
const {apiFetch} = wp;

import InstallPluginStep from './InstallPluginStep';
import ProPluginStep from './ProPluginsStep';
import OptionStep from './OptionStep';
import ImportingStep from './ImportingStep';
import ReduxTemplatesPremiumBox from './ReduxTemplatesPremiumBox';
import ReduxTemplatesPremiumActivate from './ReduxTemplatesPremiumActivate';
import ReduxTemplatesActivateBox from './ReduxTemplatesActivateBox';

import {requiresInstall, requiresPro, requiresReduxPro, isReduxProInstalled} from '~redux-templates/stores/dependencyHelper'

import '../modals.scss'
import './style.scss'

const PRO_STEP = 0;
const PLUGIN_STEP = 1;
const OPTION_STEP = 2;
const IMPORT_STEP = 3;
const REDUX_PRO_STEP = -10;
const REDUX_PRO_ACTIVATE_STEP = -9;
const REDUX_ACTIVATE_STEP = 999;
const tourPlugins = ['qubely', 'kioken-blocks'];

function ImportWizard(props) {
    const {startImportTemplate, setImportingTemplate, setActivateDialogDisplay, appendErrorMessage} = props;
    const {isChallengeOpen, importingTemplate, activateDialogDisplay, isPostEmpty, isInstalledDependencies} = props;
    const [currentStep, setCurrentStep] = useState(PRO_STEP);
    const [importing, setImporting] = useState(false);
    const [activating, setActivating] = useState(false);
    const [missingPlugins, setMissingPlugins] = useState([]);

    useEffect(() => {
        if (importingTemplate) {
        	if ( !importingTemplate.proDependenciesMissing ) {
		        importingTemplate.proDependenciesMissing = [];
	        }
	        if ( !importingTemplate.installDependenciesMissing ) {
		        importingTemplate.installDependenciesMissing = [];
	        }
            // IMPORTANT First check: can you use redux pro?
            const leftTry = isNaN(redux_templates.left) === false ? parseInt(redux_templates.left) : 0;
            if ((!!(redux_templates.mokama) === false) && leftTry < 1 && currentStep !== REDUX_PRO_ACTIVATE_STEP ) {
            	if ( currentStep !== REDUX_ACTIVATE_STEP ) {
		            setCurrentStep(REDUX_PRO_STEP);
		            return;
	            }
            }
            /* Redux pro check */
            if (requiresReduxPro(importingTemplate)) {
	            if (currentStep !== REDUX_PRO_ACTIVATE_STEP) setCurrentStep(REDUX_PRO_STEP);
                return;
            }
            // Start with Pro step
            // When all OK with Pro Step, move to Plugin Step, on the way, prepare reduxProMergedPlugins.
            if (importingTemplate && currentStep === PRO_STEP && requiresPro(importingTemplate) === false) {
                setCurrentStep(PLUGIN_STEP);
                if (isReduxProInstalled()) {
                    setMissingPlugins(
                        [].concat(importingTemplate.proDependenciesMissing, importingTemplate.installDependenciesMissing)
                            .filter(plugin => plugin)
                    );
                } else {
	                setMissingPlugins(importingTemplate.installDependenciesMissing.filter(plugin => plugin));
                }

            }
            if (importingTemplate && currentStep === PLUGIN_STEP &&  requiresInstall(importingTemplate) === false)
                if (isPostEmpty === false) setCurrentStep(OPTION_STEP); else setCurrentStep(IMPORT_STEP);
            if (importingTemplate && currentStep === OPTION_STEP && isPostEmpty === true)
                setCurrentStep(IMPORT_STEP);
            if (importingTemplate && currentStep === IMPORT_STEP && importing === false) {
                setImporting(true);
                try {
                    startImportTemplate();
                } catch (e) {
                    console.log('importing exception', e);
                    setImporting(false);
                    setCurrentStep(PLUGIN_STEP);
                    setImportingTemplate(null);
                }
            }
        }
    }, [importingTemplate, currentStep, activateDialogDisplay])

    // Activate dialog display
    useEffect(() => {
        if (activateDialogDisplay === true) { // Activate dialog hard reset case
            setCurrentStep(REDUX_ACTIVATE_STEP);
            setActivateDialogDisplay(false);
        }
    }, [activateDialogDisplay]);

    // On the initial loading
    useEffect(() => {
        setActivateDialogDisplay(false);
    }, []);

    const toNextStep = () => {
        if (isChallengeOpen) return;
        setCurrentStep(currentStep + 1);
    };

	const toPluginStep = () => {
		setCurrentStep(PRO_STEP);
	};
	const toProActivateStep = () => {
		setCurrentStep(REDUX_PRO_ACTIVATE_STEP);
	};

    const onCloseWizard = () => {
        if (isChallengeOpen) return; // When in tour mode, we don't accept mouse event.
        if (importing) return;
        setCurrentStep(PLUGIN_STEP);
        setImportingTemplate(null);
    };

    const activateReduxTracking = () => {
        setActivating(true);
	    apiFetch({path: 'redux/v1/templates/activate'}).then(response => {
		    if (response.success) {
			    redux_templates.left = response.data.left;
		    }
		    setCurrentStep(PRO_STEP);
		    setActivating(false);
	    }).catch(error => {
		    appendErrorMessage(error.code + ' : ' + error.message);
		    setCurrentStep(PRO_STEP);
		    setActivating(false);
	    });
    }


    if (isChallengeOpen) {
        // exception handling for tour mode
        if (currentStep !== PLUGIN_STEP) setCurrentStep(PLUGIN_STEP)
    }

    if (!importingTemplate) return null;
    return (
        <div className="redux-templates-modal-overlay">
            <div className="redux-templates-modal-wrapper" data-tut="tour__import_wizard">
                <div className="redux-templates-modal-header">
                    <h3>{__('Import Wizard', redux_templates.i18n)}</h3>
                    <button className="redux-templates-modal-close" onClick={onCloseWizard}>
                        <i className={'fas fa-times'}/>
                    </button>
                </div>
                <div className="redux-templates-importmodal-content">
                    {(currentStep === PRO_STEP) && requiresPro(importingTemplate) &&
                        <ProPluginStep missingPros={importingTemplate.proDependenciesMissing } onCloseWizard={onCloseWizard} />}
                    {(currentStep === PLUGIN_STEP) &&
                        <InstallPluginStep missingPlugins={isChallengeOpen ? tourPlugins : missingPlugins} toNextStep={toNextStep}
                        onCloseWizard={onCloseWizard}/>}
                    {currentStep === OPTION_STEP && <OptionStep toNextStep={toNextStep} onCloseWizard={onCloseWizard} />}
                    {currentStep === IMPORT_STEP && <ImportingStep />}
	                {currentStep === REDUX_ACTIVATE_STEP && <ReduxTemplatesActivateBox onActivateRedux={activateReduxTracking} activating={activating} />}
	                {currentStep === REDUX_PRO_ACTIVATE_STEP && <ReduxTemplatesPremiumActivate toPluginStep={toPluginStep} />}
	                {currentStep === REDUX_PRO_STEP && <ReduxTemplatesPremiumBox toProActivateStep={toProActivateStep} />}
                    {isInstalledDependencies && <iframe src='./' width="0" height="0" />}
                </div>
            </div>
        </div>
    );
}


export default compose([
    withDispatch((dispatch) => {
        const {setImportingTemplate, setActivateDialogDisplay, appendErrorMessage} = dispatch('redux-templates/sectionslist');
        return {
            setImportingTemplate,
            setActivateDialogDisplay,
            appendErrorMessage
        };
    }),

    withSelect((select, props) => {
        const {getChallengeOpen, getImportingTemplate, getActivateDialogDisplay, getInstalledDependencies} = select('redux-templates/sectionslist');
        const {isEditedPostEmpty} = select('core/editor');
        return {
            isChallengeOpen: getChallengeOpen(),
            importingTemplate: getImportingTemplate(),
            activateDialogDisplay: getActivateDialogDisplay(),
            isPostEmpty: isEditedPostEmpty(),
            isInstalledDependencies: getInstalledDependencies()
        };
    })
])(ImportWizard);
