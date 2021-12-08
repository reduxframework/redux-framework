module.exports = api => {
    api.cache( false )

    return {
        presets: [
            [ '@babel/preset-env', { modules: false } ],
            '@babel/preset-react',
        ],
        env: {
            test: {
                plugins: [
                    '@babel/plugin-transform-modules-commonjs',
                    '@babel/plugin-proposal-class-properties',
                ],
            },
        },
    }
}
