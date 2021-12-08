const path = require( 'path' )

module.exports = {
    rootDir: path.resolve( __dirname ),

    // Override the setup with some of our own stuff.
    setupFilesAfterEnv: [
        '<rootDir>/src/test/setup-test-framework.js',
    ],

    // Custom mappers.
    moduleNameMapper: {
        '^~redux-templates(.*)$': '<rootDir>/src$1',
        '.*\\.s?css$': '<rootDir>/src/test/scss-stub.js',
        '.*\\.(png|jpg|gif)$': '<rootDir>/src/test/image-stub.js',
        '.*\\.svg$': '<rootDir>/src/test/svgr-mock.js',
        '@wordpress/ajax': '<rootDir>/src/test/ajax-stub.js',
        '@wordpress/codeEditor': '<rootDir>/src/test/ajax-stub.js',
    },

    transform: {
        '^.+\\.[jt]sx?$': '<rootDir>/node_modules/babel-jest',
        '\\.mp4$': '<rootDir>/src/test/file-transformer.js',
    },

    // Ignore Unexpected identifiers in node_modules/simple-html-tokenizer/dist/es6/tokenizer.js
    transformIgnorePatterns: [
        '<rootDir>/node_modules/(?!simple-html-tokenizer)',
    ],

    // All relevant code should be included in coverage.
    collectCoverageFrom: [
        'src/(block|components|icons|welcome|help|format-types|higher-order)/**/*.js',
        '!src/block/ghost-button/**/*', // Deprecated block, don't test anymore.
        '!src/block/pullquote/**/*', // Deprecated block, don't test anymore.
        '!**/__test__/**/*',
    ],

    testMatch: [ '**/__tests__/**/*.[jt]s?(x)', '**/?(*.)+(spec|test).[jt]s?(x)' ],

    testPathIgnorePatterns: [ '/node_modules/' ],
    snapshotSerializers: ['enzyme-to-json/serializer']
}
