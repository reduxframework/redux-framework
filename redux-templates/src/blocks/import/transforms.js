/**
 * WordPress dependencies
 */
const { createBlock } = wp.blocks;

const transforms = {
    from: [
        {
            type: 'files',
            isMatch( files ) {
                return files[ 0 ].type === 'application/json';
            },
            // We define a lower priorty (higher number) than the default of 10. This
            // ensures that the Import block is only created as a fallback.
            priority: 13,
            transform: ( files ) => {
                const blocks = [];

                blocks.push( createBlock( 'redux/import', {
                    file: files,
                } ) );

                return blocks;
            },
        },
    ],
};

export default transforms;
