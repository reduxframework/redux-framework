import { __, sprintf } from '@wordpress/i18n'
import { useGlobalStore } from '../../state/GlobalState'
import { useUserStore } from '../../state/User'

export default function Toolbar({ className }) {
    const remainingImports = useUserStore(state => state.remainingImports)
    const apiKey = useUserStore(state => state.apiKey)
    const allowedImports = useUserStore(state => state.allowedImports)
    const metaData = useGlobalStore(state => state.metaData)
    const setOpen = useGlobalStore(state => state.setOpen)

    return <div className={className}>
        <div className="flex justify-between items-center px-6 sm:px-12 h-full">
            <div className="flex space-x-12 h-full">
                <div className="bg-transparent font-bold flex items-center space-x-1.5 lg:w-72">
                    <svg className="" width="30" height="30" viewBox="0 0 103 103" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect y="25.75" width="70.8125" height="77.25" fill="#000000"/>
                        <rect x="45.0625" width="57.9375" height="57.9375" fill="#37C2A2"/>
                    </svg>
                    <span className="text-sm transform translate-y-0.5 whitespace-nowrap">
                        {__('Extendify Library', 'extendify-sdk')}
                    </span>
                </div>
                {!apiKey.length && <>
                    <div className="items-center ml-8 h-full hidden md:flex">
                        <div className="m-0 p-0 px-6 text-sm bg-gray-50 border-l border-gray-300 h-full flex items-center whitespace-nowrap">
                            {sprintf(
                                __('Imports left: %s / %s'), remainingImports(), Number(allowedImports),
                            )}
                        </div>
                        <div className="h-full items-center border-l hidden lg:flex">
                            {metaData?.banners?.library_header && <>
                                {metaData.banners.library_header?.image &&
                                    <a
                                        className="h-full block"
                                        target="_blank"
                                        rel="noreferrer"
                                        href={metaData.banners.library_header.url}>
                                        <img
                                            src={metaData.banners.library_header.image}
                                            alt="Extendify notice"/>
                                    </a>
                                }
                                {!metaData.banners.library_header?.image &&
                                    <div className="text-gray-900 space-x-6 bg-extendify-light px-6 p-2 h-full flex items-center">
                                        <span className="font-bold text-left">{metaData.banners.library_header.text_backup}</span>
                                        {metaData.banners.library_header?.url && <div>
                                            <a
                                                className="button-extendify-main"
                                                target="_blank"
                                                rel="noreferrer"
                                                href={`${metaData.banners.library_header.url}&utm_source=${encodeURIComponent(window.extendifySdkData.source)}&utm_medium=library&utm_campaign=banner`}>
                                                {metaData.banners.library_header?.button_text ?? __('Get it now', 'extendify-sdk')}
                                            </a>
                                        </div>}
                                    </div>
                                }
                            </>}
                        </div>
                    </div>
                </>}
            </div>
            <div className="space-x-2 transform sm:translate-x-6">
                <button type="button" className="components-button has-icon" onClick={() => setOpen(false)}>
                    <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" size="24" role="img" aria-hidden="true" focusable="false"><path d="M13 11.8l6.1-6.3-1-1-6.1 6.2-6.1-6.2-1 1 6.1 6.3-6.5 6.7 1 1 6.5-6.6 6.5 6.6 1-1z"></path></svg>
                    <span className="sr-only">{__('Close library', 'extendify-sdk')}</span>
                </button>
            </div>
        </div>
    </div>
}
