const tailwind = require('./tailwind.config')
const semver = require('semver')
const requiredNodeVersion = require('./package').engines.node

if (!semver.satisfies(process.version, requiredNodeVersion)) {
    console.log(
        `Please switch to node version ${requiredNodeVersion} to build. You're currently on ${process.version}. Use FNM or NVM to manage node versions and auto switching.`,
    )
    process.exit(1)
}

module.exports = ({ mode, file }) => ({
    ident: 'postcss',
    sourceMap: mode !== 'production',
    plugins: [
        require('postcss-import'),
        require('tailwindcss')({
            ...tailwind,
            // Scope the editor css separately from the frontend css.
            content: findContent(file),
            important: findImportant(file),
        }),
        (css) =>
            css.walkRules((rule) => {
                // Removes top level TW styles like *::before {}
                rule.selector.startsWith('*') && rule.remove()
            }),
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

const findContent = (file) => {
    if (file.endsWith('extendify.css')) {
        return ['./src/Library/**/*.{js,jsx}']
    }
    if (file.endsWith('extendify-onboarding.css')) {
        return ['./src/Onboarding/**/*.{js,jsx}']
    }
    if (file.endsWith('extendify-assist.css')) {
        return ['./src/Assist/**/*.{js,jsx}']
    }
    return []
}

const findImportant = (rawFile) => {
    const file = rawFile.toLowerCase()
    let tailwindPrefix = true

    const filePrefixes = {
        library: 'div.extendify',
        onboarding: 'div.extendify-onboarding',
        assist: 'div.extendify-assist',
    }

    Object.keys(filePrefixes).forEach((key) => {
        if (file.includes(key) && file.endsWith('app.css')) {
            tailwindPrefix = filePrefixes[key]
        }
    })

    return tailwindPrefix
}
