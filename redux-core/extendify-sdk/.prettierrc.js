module.exports = {
    trailingComma: 'all',
    tabWidth: 4,
    semi: false,
    singleQuote: true,
    bracketSameLine: true,
    overrides: [
        {
            files: ['**/*.css', '**/*.html'],
            options: {
                singleQuote: false,
            },
        },
    ],
}
