const getPluginInstance = (pluginKey) => {
    return redux_templates.supported_plugins[pluginKey];
}

const needsPluginInstall = (pluginKey) => {
    const pluginInstance = getPluginInstance(pluginKey);
    return !pluginInstance || pluginInstance.hasOwnProperty('version') === false;
}

const needsPluginPro = (pluginKey) => {
    const pluginInstance = getPluginInstance(pluginKey);
    return (pluginInstance && pluginInstance.hasOwnProperty('has_pro') && pluginInstance.has_pro &&
        (pluginInstance.hasOwnProperty('is_pro') === false || pluginInstance.is_pro === false));
}


const checkTemplateDependencies = (data) => {
    let missingPluginArray = [], missingProArray = [];

    if (data !== undefined && 'source' in data && data.source !== 'wp_block_patterns') { // We only want to check non wp-block-patterns.
        // Template itself check
        if ('pro' in data && data.pro) {
            if (redux_templates.mokama !== '1' && data.source === redux_templates.i18n)
                missingProArray.push(redux_templates.i18n);
        }

        // dependency blocks check
        if ('blocks' in data) {
            Object.keys(data.blocks).forEach(pluginKey => {
                if (pluginKey === 'core') {
                    pluginKey = 'gutenberg';
                }
                if (needsPluginInstall(pluginKey)) missingPluginArray.push(pluginKey);
                if (needsPluginPro(pluginKey) && data.blocks[pluginKey].pro) missingProArray.push(pluginKey);
            });
        }
    }

    return {missingPluginArray, missingProArray};
}

const pluginInfo = (pluginKey) => {
    const pluginInstance = getPluginInstance(pluginKey);
    if (!pluginInstance) return {name: null, slug: null, url: null};
    const name = pluginInstance.name;
    const slug = pluginInstance.slug ? pluginInstance.slug : pluginKey;
    const url = pluginInstance.url;
    return {name, slug, url};
}

export default {checkTemplateDependencies, pluginInfo};
