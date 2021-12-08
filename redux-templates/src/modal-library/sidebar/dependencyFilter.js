const {Fragment} = wp.element;
const {compose} = wp.compose;
const {withDispatch, withSelect} = wp.data;
const {__} = wp.i18n;
import ChallengeDot from '~redux-templates/challenge/tooltip/ChallengeDot';

import {CheckboxControl, Tooltip, Button, ButtonGroup} from '@wordpress/components';
import DependencyFilterRow from './dependencyFilterRow';
import {pluginInfo} from '~redux-templates/stores/dependencyHelper';
import {REDUXTEMPLATES_PRO_KEY, NONE_KEY} from '~redux-templates/stores/helper';

function DependencyFilter(props) {
    const {dependencyFilters, activeItemType, loading, wholePlugins, thirdPartyPlugins, dependencyFilterRule} = props;
    const {setDependencyFilters, selectDependencies, setDependencyFilterRule} = props;
    // Give the selected category(activeCategory) label className as "active"
    const isNoneChecked = () => {
        if (dependencyFilters.hasOwnProperty(NONE_KEY))
            return dependencyFilters[NONE_KEY].hasOwnProperty('value') ? dependencyFilters[NONE_KEY].value : dependencyFilters[NONE_KEY];
        return false;
    };

    const toggleNoneChecked = () => {
        setDependencyFilters({...dependencyFilters,
            [NONE_KEY]: { value: dependencyFilters[NONE_KEY].value === false, disabled: dependencyFilters[NONE_KEY]['disabled'] === true }
        });
    };
    return (
        <Fragment>
            {!loading && wholePlugins &&
                <div id="redux-templates-filter-dependencies" data-tut="tour__filter_dependencies">
	                <>
		                <ButtonGroup style={{float:'right'}}>
			                <Tooltip text={__('Templates which contain blocks from any of the selected plugins.', redux_templates.i18n)} position="top center">
				                <Button isSmall isPrimary={dependencyFilterRule === false} isSecondary={dependencyFilterRule} onClick={() => setDependencyFilterRule(false)} disabled={activeItemType === 'collection'}>{__('Any', redux_templates.i18n)}</Button>
			                </Tooltip>
			                <Tooltip text={__('Templates that only contain blocks from the selected plugins.', redux_templates.i18n)} position="top center">
				                <Button isSmall isSecondary={dependencyFilterRule === false} isPrimary={dependencyFilterRule} onClick={() => setDependencyFilterRule(true)} disabled={activeItemType === 'collection'}>{__('Only', redux_templates.i18n)}</Button>
			                </Tooltip>
		                </ButtonGroup>
		                <h3>{__('Required Plugins', redux_templates.i18n)} </h3>
	                </>
                    <div className='redux-templates-select-actions'>
                        <Tooltip text={__('Select All', redux_templates.i18n)} position="top center">
	                        <a href="#" onClick={() => selectDependencies('all')}>{__('All', redux_templates.i18n)}</a>
                        </Tooltip>
		                    <span>&nbsp; / &nbsp;</span>
	                    <Tooltip text={__('Installed Dependencies', redux_templates.i18n)} position="top center">
		                    <a href="#" onClick={() => selectDependencies('installed')}>{__('Installed', redux_templates.i18n)}</a>
	                    </Tooltip>
                        <span>&nbsp; / &nbsp;</span>
	                    <Tooltip text={__('Native Blocks Only', redux_templates.i18n)} position="top center">
		                    <a href="#" onClick={() => selectDependencies('none')}>{__('None', redux_templates.i18n)}</a>
	                    </Tooltip>
                        <ChallengeDot step={2} />
                    </div>
                    <ul className="redux-templates-sidebar-dependencies">
                        { (loading === false) &&
                            <li style={{display: activeItemType === 'collection' ? 'none': ''  }}>
                                <CheckboxControl
                                    label={__('Native', redux_templates.i18n)}
                                    checked={isNoneChecked()}
                                    onChange={toggleNoneChecked}
                                />
                                <Tooltip text={__('Only default WordPress blocks used.', redux_templates.i18n)} position='top center'>
                                    <span style={{float:'right', marginRight:'2px'}}><i className="fa fa-info-circle" /></span>
                                </Tooltip>
                            </li>
                        }
                        {
                            Object.keys(dependencyFilters)
                                .filter(pluginKey => (wholePlugins.indexOf(pluginKey)!==-1 || pluginKey === REDUXTEMPLATES_PRO_KEY))
                                .sort((a, b) => {
                                    const pluginInstanceA = pluginInfo(a);
                                    const pluginInstanceB = pluginInfo(b);
                                    if (!pluginInstanceB.name || pluginInstanceA.name < pluginInstanceB.name)
                                        return -1;
                                    if (!pluginInstanceA.name || pluginInstanceA.name > pluginInstanceB.name)
                                        return 1;
                                    return 0;
                                })
                                .map(pluginKey =>
                                    <DependencyFilterRow key={pluginKey} pluginKey={pluginKey} />
                                )
                        }
                    </ul>
                    <div role="group" className="components-button-group" style={{float: 'right'}}>
	                    <Tooltip text={__('Third-party premium plugins required.', redux_templates.i18n)} position='top center'>
		                    <span style={{float:'right', marginRight:'15px', cursor:'pointer'}}><i className="fa fa-info-circle" /></span>
	                    </Tooltip>
                    </div>

                    <h3>Third-Party Premium</h3>
                    <ul className="redux-templates-sidebar-dependencies">
                        {
                            thirdPartyPlugins
                                .sort((a, b) => {
                                    const pluginInstanceA = pluginInfo(a);
                                    const pluginInstanceB = pluginInfo(b);
                                    if (!pluginInstanceB.name || pluginInstanceA.name < pluginInstanceB.name)
                                        return -1;
                                    if (!pluginInstanceA.name || pluginInstanceA.name > pluginInstanceB.name)
                                        return 1;
                                    return 0;
                                })
                                .map(pluginKey =>
                                    <DependencyFilterRow key={pluginKey} pluginKey={pluginKey} />
                                )
                        }
                    </ul>
                </div>
            }
        </Fragment>
    );
}

export default compose([
    withDispatch((dispatch) => {
        const {setDependencyFilters, selectDependencies, setDependencyFilterRule} = dispatch('redux-templates/sectionslist');
        return {
            setDependencyFilters,
            selectDependencies,
            setDependencyFilterRule
        };
    }),

    withSelect((select) => {
        const {getDependencyFiltersStatistics, getLoading, getActiveItemType, getWholePlugins, getThirdPartyPlugins, getDependencyFilterRule} = select('redux-templates/sectionslist');
        return {
            loading: getLoading(),
            dependencyFilters: getDependencyFiltersStatistics(),
            wholePlugins: getWholePlugins(),
            thirdPartyPlugins: getThirdPartyPlugins(),
            dependencyFilterRule: getDependencyFilterRule(),
	        activeItemType: getActiveItemType()
        };
    })
])(DependencyFilter);
