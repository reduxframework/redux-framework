import React from 'react';
const {useState, useEffect, useRef} = wp.element;
const {compose} = wp.compose;
const {withDispatch, withSelect} = wp.data;
const {__} = wp.i18n;

import {CheckboxControl, Tooltip} from '@wordpress/components';
import {pluginInfo} from '~redux-templates/stores/dependencyHelper';
import {NONE_KEY} from '~redux-templates/stores/helper';

const specialPlugins = ['gutenberghub.com', 'editorplus'];

function DependencyFilterRow(props) {
    const {pluginKey, dependencyFilters} = props;
    const {setDependencyFilters} = props;
    const [isValidPlugin, setIsValidPlugin] = useState(false);
    const [isChecked, setIsChecked] = useState(false);
    const [pluginInstanceURL, setPluginInstanceURL] = useState('');
    const [pluginInstanceName, setPluginInstanceName] = useState('');
    const [pluginClassname, setPluginClassname] = useState('');

    useEffect(() => {
        const pluginInstance = pluginInfo(pluginKey);
        if (!pluginKey || pluginKey === NONE_KEY) {
            setIsValidPlugin(false);
            return;
        }
        if (!pluginInstance || pluginInstance.name == null) {
            setIsValidPlugin(false);
            return;
        }
        setPluginInstanceURL(pluginInstance.url);
        setPluginInstanceName(pluginInstance.name);
        setIsValidPlugin(true);
    }, [pluginKey]);

    useEffect(() => {
        const pluginInstance = pluginInfo(pluginKey);
        if (!dependencyFilters) return;
        if (dependencyFilters.hasOwnProperty(pluginKey)) {
            if (dependencyFilters[pluginKey].disabled)
                setIsChecked(false);
            else
                setIsChecked(dependencyFilters[pluginKey].hasOwnProperty('value') ? dependencyFilters[pluginKey].value : dependencyFilters[pluginKey]);
        } else
            setIsChecked(false)
        let pluginClassnameList = [];
        pluginClassnameList.push(!pluginInstance.version && !('no_plugin' in pluginInstance) ? 'missing-dependency' : '');
        pluginClassnameList.push((!dependencyFilters[pluginKey] || dependencyFilters[pluginKey].disabled) ? 'disabled' : '');
        setPluginClassname(pluginClassnameList.join(' '));
    }, [JSON.stringify(dependencyFilters)])

    const toggleChecked = () => {
        // disable check first
        if (dependencyFilters[pluginKey] === null || dependencyFilters[pluginKey] === undefined || dependencyFilters[pluginKey].disabled) return;
        // reflect on the item click event.
        let newDependencyFilters = {
            ...dependencyFilters,
            [pluginKey]: { value: dependencyFilters[pluginKey].value === false, disabled: dependencyFilters[pluginKey]['disabled'] === true }
        };

        // gutenberg.com, EditorPlus check
        if (specialPlugins.includes(pluginKey)) {
            specialPlugins.forEach((plugin) => {
                newDependencyFilters = {
                    ...newDependencyFilters,
                    [plugin]: { value: dependencyFilters[pluginKey].value === false, disabled: dependencyFilters[plugin]['disabled'] === true }
                }
            })
        }

        // if no item is selected, activate native, other wise conider to deactivate native
        // let valueCount = groupBy(Object.keys(newDependencyFilters), key => (newDependencyFilters[key] === true || newDependencyFilters[key].value === true));
        setDependencyFilters({...newDependencyFilters});
    };

    if (isValidPlugin === false) return null;

    return (
        <li className={pluginClassname}>
            <CheckboxControl
                label={pluginInstanceName}
                checked={isChecked}
                onChange={toggleChecked}
            />
            {pluginInstanceURL ?
	            <Tooltip position={'top center'} text={__('Visit Plugin Website', redux_templates.i18n)}><span><a href={pluginInstanceURL} target="_blank">
                    <i className="fas fa-external-link-alt" />
	            </a></span></Tooltip> : null}
	        {pluginClassname.includes('missing-dependency') &&
	        <Tooltip position={'top center'} text={__('Not Installed', redux_templates.i18n)}><span className='redux-icon-wrapper'><i className="fa fa-exclamation-triangle" /></span></Tooltip>
	        }
        </li>
    );
}


export default compose([
    withDispatch((dispatch) => {
        const {setDependencyFilters} = dispatch('redux-templates/sectionslist');
        return {
            setDependencyFilters
        };
    }),

    withSelect((select) => {
        const {getDependencyFiltersStatistics, getLoading, getActiveCategory} = select('redux-templates/sectionslist');
        return {
            loading: getLoading(),
            dependencyFilters: getDependencyFiltersStatistics(),
            activeCategory: getActiveCategory()
        };
    })
])(DependencyFilterRow);
