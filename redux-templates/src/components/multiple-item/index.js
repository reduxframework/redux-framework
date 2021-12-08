import ButtonGroup from '../button-group';

const {__} = wp.i18n
import {Tooltip} from '@wordpress/components';
import {requiresInstall, requiresPro} from '~redux-templates/stores/dependencyHelper';
import SafeImageLoad from '~redux-templates/components/safe-image-load';
import './style.scss'

const MultipleItem = (props) => {

    const {data, onSelectCollection} = props;
    const {pages, homepageData, ID, name} = data;
    const {image} = homepageData || {};

    return (
        <div className="redux-templates-multiple-template-box">
            <div className="multiple-template-view" onClick={ () => onSelectCollection( ID ) } >
                <div className="redux-templates-box-shadow">
                    <div className="redux-templates-default-template-image">
                        <SafeImageLoad url={image} alt={__('Default Template', redux_templates.i18n)} />
                        {requiresPro(data) && <span className="redux-templates-pro-badge">{__('Premium', redux_templates.i18n)}</span>}
                        {!requiresPro(data) && requiresInstall(data) && <div className="redux-templates-missing-badge"><i className="fas fa-exclamation-triangle" /></div>}
                    </div>
                    <div className="redux-templates-button-overlay">
                        {requiresPro(data) && <Tooltip text={__('Premium Requirements', redux_templates.i18n)} position="bottom" key={data.source+data.source_id}><span className="redux-templates-pro-badge">{__('Premium', redux_templates.i18n)}</span></Tooltip>}
                        {!requiresPro(data) && requiresInstall(data) && <Tooltip text={__('Not Installed', redux_templates.i18n)} position="bottom" key={data.source+data.source_id}><div className="redux-templates-missing-badge"><i className="fas fa-exclamation-triangle" /></div></Tooltip>}
                        <div className="redux-templates-import-button-group">
                            <div className="action-buttons"><a className="redux-templates-button download-button">{__('View Templates', redux_templates.i18n)}</a></div>
                        </div>
                    </div>
                </div>
                <div className="redux-templates-tmpl-info">
                    <h5 className="redux-templates-tmpl-title" dangerouslySetInnerHTML={{__html:name}}/>
                    <span className="redux-templates-temp-count">{ pages ? pages.length : 0 } {__('Templates', redux_templates.i18n)}</span>
                </div>
            </div>
        </div>
    );
}

export default MultipleItem
