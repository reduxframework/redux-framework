const {Fragment} = wp.element;
const {__} = wp.i18n;

import ReduxTemplatesPremiumBox from './ReduxTemplatesPremiumBox';
import {pluginInfo} from '~redux-templates/stores/dependencyHelper';
const REDUXTEMPLATES_PRO_KEY = 'redux-pro';
export default function ProPluginStep(props) {
    const {missingPros, onCloseWizard} = props;

    if ( missingPros.indexOf(REDUXTEMPLATES_PRO_KEY) >= 0 ) return <ReduxTemplatesPremiumBox />
    return (
        <Fragment>
            <div className="redux-templates-modal-body">
                <h5>{__('Additional Plugins Required', redux_templates.i18n)}</h5>
                <p>{__('The following premium plugin(s) are required to import this template:', redux_templates.i18n)}</p>
                <ul className="redux-templates-import-progress">
                    {
                        missingPros.map(pluginKey => {
                            let plugin = pluginInfo(pluginKey)
                            return (
                                <li className='installing' key={pluginKey}>
                                    {plugin.name} {plugin.url &&
                                <a href={plugin.url} target="_blank"><i className="fas fa-external-link-alt"/></a>
                                }
                                </li>);
                        })
                    }
                </ul>

            </div>
            <div className="redux-templates-modal-footer">
                <a className="button button-secondary" onClick={onCloseWizard}>
                    {__('Close', redux_templates.i18n)}
                </a>
            </div>
        </Fragment>
    );
}

