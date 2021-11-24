import { __ } from '@wordpress/i18n'
import { InspectorAdvancedControls } from '@wordpress/block-editor'
import { createHigherOrderComponent } from '@wordpress/compose'
import { addFilter } from '@wordpress/hooks'
import { FormTokenField } from '@wordpress/components'

import suggestions from '../../utility-framework/suggestions.json'

function addAttributes(settings) {
    // Add new extUtilities attribute to block settings.
    return {
        ...settings,
        attributes: {
            ...settings.attributes,
            extUtilities: {
                type: 'array',
                default: [],
            },
        },
    }
}

function addEditProps(settings) {
    const existingGetEditWrapperProps = settings.getEditWrapperProps
    settings.getEditWrapperProps = (attributes) => {
        let props = {}

        if (existingGetEditWrapperProps) {
            props = existingGetEditWrapperProps(attributes)
        }

        return addSaveProps(props, settings, attributes)
    }

    return settings
}

// Create HOC to add Extendify Utility to Advanced Panel of block.
const utilityClassEdit = createHigherOrderComponent((BlockEdit) => {
    return function editPanel(props) {
        const { extUtilities: classes } = props.attributes
        const suggestionList = suggestions.suggestions.map((s) => {
            // Remove all extra // and . from classnames
            return s.replace('.', '').replace(new RegExp('\\\\', 'g'), '')
        })

        return (
            <>
                <BlockEdit {...props} />
                {classes && (
                    <InspectorAdvancedControls>
                        <FormTokenField
                            label={__('Extendify Utilities', 'extendify-sdk')}
                            tokenizeOnSpace={true}
                            value={classes}
                            suggestions={suggestionList}
                            onChange={(value) => {
                                props.setAttributes({
                                    extUtilities: value,
                                })
                            }}
                        />
                    </InspectorAdvancedControls>
                )}
            </>
        )
    }
}, 'utilityClassEdit')

function addSaveProps(saveElementProps, blockType, attributes) {
    let { className: generatedClasses } = saveElementProps
    let { extUtilities: classes, className: additionalClasses } = attributes

    if (!classes || !Object.keys(classes).length) {
        return saveElementProps
    }

    // EK seems to be converting string values to objects in some situations
    const normalizeAsArray = (item) => {
        switch (Object.prototype.toString.call(item)) {
            case '[object String]':
                return item.split(' ')
            case '[object Array]':
                return item
            default:
                return []
        }
    }
    const classesCombined = new Set([
        ...normalizeAsArray(additionalClasses),
        ...normalizeAsArray(generatedClasses),
        ...normalizeAsArray(classes),
    ])

    return Object.assign({}, saveElementProps, {
        className: [...classesCombined].join(' '),
    })
}

addFilter(
    'blocks.registerBlockType',
    'extendify/utilities/attributes',
    addAttributes,
)

addFilter(
    'blocks.registerBlockType',
    'extendify/utilities/addEditProps',
    addEditProps,
)

addFilter(
    'editor.BlockEdit',
    'extendify/utilities/advancedClassControls',
    utilityClassEdit,
)

addFilter(
    'blocks.getSaveContent.extraProps',
    'extendify/utilities/extra-props',
    addSaveProps,
)
