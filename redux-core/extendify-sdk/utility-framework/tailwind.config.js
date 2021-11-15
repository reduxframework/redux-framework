const suggestions = require('./suggestions.config').suggestions

module.exports = {
    purge: {
        enabled: true,
        content: [],
        safelist: {
            greedy: suggestions?.map(s => new RegExp(`^${s}$`)) ?? [],
        },
    },
    prefix: 'ext-',
    important: true,
    darkMode: false,
    theme: {
        screens: {
            tablet: '782px',
            desktop: '1080px',
        },
        spacing: {
            0: '0',
            base: 'var(--wp--style--block-gap, 2rem)',
            lg: 'var(--extendify--spacing--large)',
        },
    },
    variants: {
        gap: [],
        gridColumn: [],
        gridColumnEnd: [],
        gridColumnStart: [],
        gridRow: [],
        gridRowEnd: [],
        gridRowStart: [],
        gridTemplateRows: [],
        lineHeight: [],
        borderRadius: [],
        borderWidth: [],
    },
    plugins: [],
    corePlugins: {
        preflight: false,
        animation: false,
        container: false,
    },
}
