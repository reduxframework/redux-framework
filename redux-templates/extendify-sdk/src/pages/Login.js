// import { useEffect } from '@wordpress/element'
import LoginInterface from '../components/LoginInterface'
import { __ } from '@wordpress/i18n'
import { useGlobalStore } from '../state/GlobalState'

export default function Login({ className }) {

    return <div className={className}>
        <a href="#extendify-templates" className="sr-only focus:not-sr-only focus:text-blue-500">
            {__('Skip to content', 'extendify-sdk')}
        </a>
        <div className="flex sm:space-x-12 relative mx-auto max-w-screen-4xl h-full">
            <div className="absolute flex inset-0 items-center justify-center z-20 sm:space-x-12">
                <div className="pl-12 py-6 absolute top-0 left-0">
                    <button
                        type="button"
                        className="cursor-pointer text-black bg-transparent font-medium flex items-center p-3 transform -translate-x-3 button-focus"
                        onClick={() => useGlobalStore.setState({
                            currentPage: 'content',
                        })}>
                        <svg className="fill-current" width="8" height="12" viewBox="0 0 8 12" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.70998 9.88047L2.82998 6.00047L6.70998 2.12047C7.09998 1.73047 7.09998 1.10047 6.70998 0.710469C6.31998 0.320469 5.68998 0.320469 5.29998 0.710469L0.70998 5.30047C0.31998 5.69047 0.31998 6.32047 0.70998 6.71047L5.29998 11.3005C5.68998 11.6905 6.31998 11.6905 6.70998 11.3005C7.08998 10.9105 7.09998 10.2705 6.70998 9.88047Z"/>
                        </svg>
                        <span className="ml-4">{__('Go back', 'extendify-sdk')}</span>
                    </button>
                </div>
                <div className="flex justify-center">
                    <LoginInterface/>
                </div>
            </div>
        </div>
    </div>
}
