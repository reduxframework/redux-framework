import { useEffect, useState } from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'
import { CopyToClipboard } from 'react-copy-to-clipboard'

/** Overlay for pattern import button */
export const DevButtonOverlay = ({ template }) => {
    const basePatternId = template?.fields?.basePattern?.length
        ? template?.fields?.basePattern[0]
        : ''
    const [idText, setIdText] = useState(basePatternId)

    useEffect(() => {
        if (!basePatternId?.length || idText === basePatternId) return
        setTimeout(() => setIdText(basePatternId), 1000)
    }, [idText, basePatternId])

    if (!basePatternId) return null

    return (
        <div className="absolute bottom-0 left-0 z-50 mb-4 ml-4 flex items-center space-x-2 opacity-0 transition duration-100 group-hover:opacity-100 space-x-0.5">
            <CopyToClipboard
                text={template?.fields?.basePattern}
                onCopy={() => setIdText(__('Copied!', 'extendify'))}>
                <button className="text-sm rounded-md border border-black bg-white py-1 px-2.5 font-medium text-black no-underline m-0 cursor-pointer">
                    {sprintf(__('Base: %s', 'extendify'), idText)}
                </button>
            </CopyToClipboard>
            <a
                target="_blank"
                className="text-sm rounded-md border border-black bg-white py-1 px-2.5 font-medium text-black no-underline m-0"
                href={template?.fields?.editURL}
                rel="noreferrer">
                {__('Edit', 'extendify')}
            </a>
        </div>
    )
}
