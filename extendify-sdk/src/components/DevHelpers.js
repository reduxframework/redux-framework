import { useEffect, useState } from '@wordpress/element'
import { CopyToClipboard } from 'react-copy-to-clipboard'
import { __ } from '@wordpress/i18n'

/** Overlay for pattern import button */
export const DevButtonOverlay = ({ template }) => {
    const [idText, setIdText] = useState(template.id)

    useEffect(() => {
        if (idText === template.id) return
        setTimeout(() => setIdText(template.id), 1000)
    }, [idText, template.id])

    return (
        <div className="group-hover:opacity-90 opacity-0 flex space-x-2 items-center mb-2 ml-2 absolute bottom-0 left-0 transition duration-200 ease-in-out z-50">
            <CopyToClipboard
                text={template.id}
                onCopy={() => setIdText(__('Copied...', 'extendify'))}>
                <button className="bg-white border border-black p-2 rounded-md shadow-md cursor-pointer">
                    {idText}
                </button>
            </CopyToClipboard>
            <a
                target="_blank"
                className="bg-white border font-semibold border-black p-2 rounded-md shadow-md no-underline text-black"
                href={`https://airtable.com/appn5PSl8wU6X70sG/tblviYevlV5fYAEH7/viwh0L1kHmXN7FIB9/${template.id}`}
                rel="noreferrer">
                {__('Edit', 'extendify')}
            </a>
        </div>
    )
}
