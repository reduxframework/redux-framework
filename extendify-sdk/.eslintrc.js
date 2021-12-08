module.exports = {
    env: {
        browser: true,
        es2021: true,
        jest: true,
        node: true,
    },
    extends: [
        'eslint:recommended',
        'plugin:react/recommended',
        'plugin:react-hooks/recommended',
        'prettier',
    ],
    parserOptions: {
        ecmaFeatures: { jsx: true },
        sourceType: 'module',
    },
    plugins: ['react', 'prettier'],
    rules: {
        'require-await': 'error',
        quotes: ['error', 'single', { avoidEscape: true }],
        'comma-dangle': ['error', 'always-multiline'],
        'array-element-newline': ['error', 'consistent'],
        'no-constant-condition': ['error', { checkLoops: false }],
        'no-multi-spaces': ['error'],
        semi: ['error', 'never'],
        'space-in-parens': ['error', 'never'],
        'key-spacing': ['error', { afterColon: true }],
        'space-infix-ops': ['error'],
        'space-before-function-paren': [
            'error',
            {
                anonymous: 'always',
                named: 'never',
                asyncArrow: 'always',
            },
        ],
        'react/react-in-jsx-scope': 'off',
        'quote-props': ['error', 'as-needed'],
        'no-multiple-empty-lines': ['error', { max: 1 }],
        'react/prop-types': 0, // TODO: Do we want this required?
        'lines-around-comment': [
            'error',
            {
                beforeBlockComment: true,
                allowBlockStart: true,
            },
        ],
    },
    settings: { react: { version: 'detect' } },
}
