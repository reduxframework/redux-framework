const tailwind = require('./tailwind.config')
const { resolve } = require('path')
const { writeFileSync } = require('fs')

module.exports = ({ mode }) => ({
    ident: 'postcss',
    sourceMap: mode !== 'production',
    plugins: [
        require('postcss-import'),
        buildBlockList,
        require('tailwindcss')({
            ...tailwind,
            config: resolve(__dirname, 'tailwind.config.js'),
        }),
        (css) =>
            css.walkRules((rule) => {
                // Removes top level TW styles like *::before {}
                rule.selector.startsWith('*') && rule.remove()
            }),
        extractSuggestions,
        appendNots,
        // See: https://github.com/WordPress/gutenberg/blob/trunk/packages/postcss-plugins-preset/lib/index.js
        require('autoprefixer')({ grid: true }),
        mode === 'production' &&
            // See: https://github.com/WordPress/gutenberg/blob/trunk/packages/scripts/config/webpack.config.js#L68
            require('cssnano')({
                preset: [
                    'default',
                    {
                        discardComments: {
                            removeAll: true,
                        },
                    },
                ],
            }),
        require('postcss-safe-important'),
    ],
})

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
        notes: 'This file is generated in postcss.config.js. Do not edit directly.',
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
            (c) => !classesToExclude.find((e) => e.trim() === c.trim()),
        )
        writeFileSync(
            resolve(__dirname, 'suggestions.json'),
            JSON.stringify(data, null, 4),
        )
    } catch (error) {
        console.error(error)
    }
    return css
}

const appendNots = (css) => {
    css.walkRules((rule) => {
        // This appends the :not() exception to padding and margins
        ;(new RegExp('[:]?[^a-z]-?p[a-z]?-.+').test(rule) &&
            (rule.selector += ':not([style*="padding"])')) ||
            (new RegExp('[:]?[^a-z]-?m[a-z]?-.+').test(rule) &&
                (rule.selector += ':not([style*="margin"])'))
    })
}
