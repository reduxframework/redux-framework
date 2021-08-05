const path = require('path')
const camelCaseDash = (string) => string.replace(/-([a-z])/g, (_match, letter) => letter.toUpperCase())
const mix = require('laravel-mix')

// If you add additional WP imports, include them here (could we generate these?)
const externals = [
    'api-fetch',
    'block-editor',
    'blocks',
    'components',
    'compose',
    'data',
    'date',
    'htmlEntities',
    'hooks',
    'edit-post',
    'element',
    'editor',
    'i18n',
    'plugins',
    'viewport',
    'ajax',
    'codeEditor',
    'rich-text',
]
const globals = externals.reduce((externals, name) => ({
    ...externals,
    [`@wordpress/${name}`]: `wp.${camelCaseDash(name)}`,
}), {})

const webpackConfig = (context) => {
    return {
        context: context,
        externals: {
            wp: 'wp',
            lodash: 'lodash',
            fetch: 'fetch',
            react: 'React',
            'react-dom': 'ReactDOM',
            ...globals,
        },
    }
}

mix.js('src/app.js', 'public/build/extendify-sdk.js')
    .webpackConfig(webpackConfig(path.resolve(__dirname, 'src')))
    .react()
    .setPublicPath('public')
    .postCss(
        'src/app.css',
        'public/build/extendify-sdk.css',
        [require('tailwindcss')],
    )
    .browserSync({
        proxy: 'wordpress.test',
        open: false,
        files: ['src/**/*'],
    })

mix.js('editorplus/editorplus.js', 'editorplus/editorplus.min.js')
    .webpackConfig(webpackConfig(path.resolve(__dirname, 'editorplus')))
    .react()
