const path = require('path')
const camelCaseDash = (string) =>
    string.replace(/-([a-z])/g, (_match, letter) => letter.toUpperCase())
const mix = require('laravel-mix')
const fs = require('fs')
const semver = require('semver')
const requiredNodeVersion = require('./package').engines.node

if (!semver.satisfies(process.version, requiredNodeVersion)) {
    console.log(
        `Please switch to node version ${requiredNodeVersion} to build. You're currently on ${process.version}. Use FNM or NVM to manage node versions and auto switching.`,
    )
    process.exit(1)
}

mix.options({ manifest: false })

// If you add additional WP imports, include them here (could we generate these?)
const externals = [
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
const globals = externals.reduce(
    (externals, name) => ({
        ...externals,
        [`@wordpress/${name}`]: `wp.${camelCaseDash(name)}`,
    }),
    {},
)

const webpackConfig = (context) => {
    return {
        context: context,
        // Enable this if you need to see webpack warnings
        // stats: {
        //     children: true,
        // },
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

mix.browserSync({
    proxy: 'wordpress.test',
    open: false,
    files: ['src/**/*', 'utility-framework/**/*'],
})

mix.js('src/app.js', 'public/build/extendify-sdk.js')
    .webpackConfig(webpackConfig(path.resolve(__dirname, 'src')))
    .react()
    .postCss('src/app.css', 'public/build/extendify-sdk.css', [
        require('tailwindcss'),
        (css) =>
            css.walkRules((rule) => {
                rule.selector.startsWith('*') && rule.remove()
            }),
        require('postcss-safe-important'),
    ])

mix.js('editorplus/editorplus.js', 'public/editorplus/editorplus.min.js')
    .webpackConfig(webpackConfig(path.resolve(__dirname, 'editorplus')))
    .react()

// Utility specific processing
const blockList = new Set()
const buildBlockList = (css) => {
    css.walkRules((rule) => {
        // Allows us to ignore some rules from being processed/purged
        if (
            rule?.nodes.find(
                (n) => n.type === 'comment' && n.text === 'no suggestion',
            )
        ) {
            blockList.add(rule.selector.replace('.', '').split(' ')[0])
        }
    })
}

const extractSuggestions = (css) => {
    const classesToExclude = [...blockList]
    const data = {
        notes: 'This file is generated in webpack.mix.js. Do not edit directly.',
        suggestions: [],
    }
    css.walkRules((rule) => {
        data.suggestions.push(
            rule.selector
                .replace('.', '')
                .replace(new RegExp(':not\\(([^\\)]*)\\)'), '')
                .split(/ |.wp-/)[0],
        )
    })
    try {
        data.suggestions = [...new Set(data.suggestions)].filter(
            (c) => !classesToExclude.includes(c),
        )
        fs.writeFileSync(
            __dirname + '/utility-framework/suggestions.json',
            JSON.stringify(data, null, 4),
        )
    } catch (error) {
        console.error(error)
    }
    return css
}

mix.postCss(
    'utility-framework/extendify-utilities.css',
    'public/build/extendify-utilities.css',
    [
        require('postcss-import'),
        buildBlockList,
        require('tailwindcss')({
            config: 'utility-framework/tailwind.config.js',
        }),
        (css) =>
            css.walkRules((rule) => {
                // Removes top level TW styles like *::before {}
                rule.selector.startsWith('*') && rule.remove()
                // This appends the :not() exception to padding and margins
                ;(new RegExp('[:]?[^a-z]-?p[a-z]?-.+').test(rule) &&
                    (rule.selector += ':not([style*="padding"])')) ||
                    (new RegExp('[:]?[^a-z]-?m[a-z]?-.+').test(rule) &&
                        (rule.selector += ':not([style*="margin"])'))
            }),
        extractSuggestions,
    ],
)
