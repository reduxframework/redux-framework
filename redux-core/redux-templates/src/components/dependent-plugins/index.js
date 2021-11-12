import {Tooltip} from '@wordpress/components';
import * as Icons from '~redux-templates/icons'
import './style.scss'
const {__} = wp.i18n;

export default function DependentPlugins (props) {
    const {data, showDependencyBlock} = props;
    const {id} = data;

    const isMissingPlugin = (plugin) => {
        return ((data.proDependenciesMissing && data.proDependenciesMissing.indexOf(plugin) >=0)
            || (data.installDependenciesMissing && data.installDependenciesMissing.indexOf(plugin) >=0))
    }

    if (showDependencyBlock) {
	    let index = data.dependencies.indexOf('core');
	    if ( index > -1 ) {
		    data.dependencies.splice(index, 1);
		    data.dependencies.push( 'core' );
	    }
	    return (
		    <div className="redux-templates-button-display-dependencies">
			    { data.dependencies &&
			    data.dependencies.map(plugin => {
			    	let pluginInstance = null;
				    const plugin_name = plugin.replace('-pro', '').replace('-premium', '').replace(/\W/g, '').toLowerCase();
			    	if ( 'core' == plugin ) {
					    pluginInstance = {
					    	name: 'WordPress Native'
					    }
				    } else {
					    pluginInstance = redux_templates.supported_plugins[plugin];
				    }
					if ( !pluginInstance ) {
						pluginInstance = redux_templates.supported_plugins[plugin.replace('-pro', '').replace('-premium', '')];
					}

				    // We don't want two of the same icons showing up.
				    if ( ! plugin.includes('-pro') && ! plugin.includes('-premium') ) {
					    if ( data.dependencies.includes(plugin + '-pro') || data.dependencies.includes( plugin + '-premium' ) ) {
						    return;
					    }
				    }
				    if (!pluginInstance) {
					    console.log( 'Missing plugin details for '+ plugin+' - ' + plugin.replace('-pro', '').replace('-premium', '') );
					    console.log( redux_templates.supported_plugins );
					    return;
				    }
				    if ( 'redux' === plugin_name ) {
					    return;
				    }
				    const IconComponent = Icons[plugin_name];
				    if (IconComponent && pluginInstance) {
					    return (
						    <Tooltip text={(isMissingPlugin(plugin) && 'core' !== plugin ? pluginInstance.name+ ' ( '+__('Not Installed', redux_templates.i18n)+' )' : pluginInstance.name)} position="bottom center" key={id + plugin}>
                                    <span className={isMissingPlugin(plugin) && 'core' !== plugin ? 'missing-dependency' : ''}>
                                        <IconComponent/>
                                    </span>
						    </Tooltip>
					    );
				    } else if ( 'shareablockcom' !== plugin_name && 'gutenberghubcom' !== plugin_name ) {
					    console.log('Need icon for ' + plugin_name);
				    }

			    })
			    }
			    { data.dependencies['core'] &&
			    <Tooltip text={__('WordPress Core', redux_templates.i18n)} position="bottom center" key={id + 'core'}>
				            <span>
				            <IconComponent/>
				            </span>
			    </Tooltip>

			    }
		    </div>
	    );
    }

    return null;
}
