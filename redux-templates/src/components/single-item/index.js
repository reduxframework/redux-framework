import {Tooltip} from '@wordpress/components';

const {__} = wp.i18n
const {withSelect} = wp.data;
const {useState, useEffect} = wp.element;

import ButtonGroup from '../button-group';
import SafeImageLoad from '~redux-templates/components/safe-image-load';
import BackgroundImage from '../background-image';
import {requiresInstall, requiresPro} from '~redux-templates/stores/dependencyHelper';

import './style.scss'


function SingleItem (props) {
    // Decoupling props
    const {pageData, tourActiveButtonGroup, index} = props;
    const [data, setData] = useState(null);
    // const {ID, image, url, pro, source, requirements} = data;
    const [innerClassname, setInnerClassname] = useState('redux-templates-single-item-inner redux-templates-item-wrapper ');

    useEffect(() => {
        if (pageData) setData(pageData[index]);
    }, [index, pageData]);

    useEffect(() => {
        setInnerClassname((pageData && pageData[index] && tourActiveButtonGroup && tourActiveButtonGroup.ID === pageData[index].ID) ?
            'redux-templates-single-item-inner redux-templates-item-wrapper focused' : 'redux-templates-single-item-inner redux-templates-item-wrapper');
    }, [tourActiveButtonGroup, pageData, index]);

    if (!data) return null;
    return (
        <div className="redux-templates-single-section-item">
            <div className={innerClassname}>
                <div className="redux-templates-default-template-image">
                    {data.source !== 'wp_block_patterns' && <SafeImageLoad url={data.image}/> }
                    {data.source === 'wp_block_patterns' && <BackgroundImage data={data} />}
                    {requiresPro(data) && <span className="redux-templates-pro-badge">{__('Premium', redux_templates.i18n)}</span>}
                    {!requiresPro(data) && requiresInstall(data) && <span className="redux-templates-missing-badge"><i className="fas fa-exclamation-triangle" /></span>}
                    <div className="redux-templates-tmpl-title">{data.name}</div>
                </div>
                {/* redux-templates-default-template-image */}
                <div className="redux-templates-button-overlay">
	                {requiresPro(data) && <Tooltip text={__('Premium Requirements', redux_templates.i18n)} position="bottom" key={data.source+data.source_id}><div className="redux-templates-pro-badge">{__('Premium', redux_templates.i18n)}</div></Tooltip>}
                    {!requiresPro(data) && requiresInstall(data) && <Tooltip text={__('Not Installed', redux_templates.i18n)} position="bottom" key={data.source+data.source_id}><div className="redux-templates-missing-badge"><i className="fas fa-exclamation-triangle" /></div></Tooltip>}
                    <ButtonGroup index={index} showDependencyBlock={true} data={data} pageData={pageData} />
                </div>

            </div>
            {/* redux-templates-item-wrapper */}
        </div>
    )
}


export default withSelect((select, props) => {
    const {getTourActiveButtonGroup, getPageData} = select('redux-templates/sectionslist');
    return {
        pageData: getPageData(),
        tourActiveButtonGroup: getTourActiveButtonGroup()
    };
})(SingleItem);
