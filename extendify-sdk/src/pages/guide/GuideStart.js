// import { useEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { useGlobalStore } from '../../state/GlobalState'
import { useUserStore } from '../../state/User'
import { General as GeneralApi } from '../../api/General'
import { useEffect, useRef } from '@wordpress/element'
import { useTemplatesStore } from '../../state/Templates'

export default function GuideStart() {
    const preferred = useUserStore(state => state.preferredOptions)
    const templates = useTemplatesStore(state => state.templates)
    const closeGuide = () => {
        GeneralApi.ping('guide-cancelled')
        templates.length && useTemplatesStore.setState({ skipNextFetch: true })
        useGlobalStore.setState({ currentPage: 'main' })
    }
    const goToWelcome = () => {
        useGlobalStore.setState({ currentPage: 'welcome' })
    }
    const setTypeAndProgress = (type) => {
        // Update their preferred type and start the guide, or if they x
        // then send them to the main screen
        type && useUserStore.getState().updatePreferredOption('type', type)
        useGlobalStore.setState({
            currentPage: type
                ? 'guide-steps'
                : 'main',
        })
    }

    const templatesRef = useRef()
    const patternsRef = useRef()

    useEffect(() => {
        GeneralApi.ping('guide-started')

        preferred?.type === 'pattern'
            ? patternsRef.current.focus()
            : templatesRef.current.focus()
    }, [preferred?.type])

    const emptyToolbar = <div className="w-full h-16 relative z-10 border-solid border-0 flex-shrink-0">
        <div className="flex justify-between items-center px-6 sm:px-12 h-full">
            <div className="flex space-x-12 h-full">
            </div>
            <div className="space-x-2 transform sm:translate-x-8">
                <button
                    type="button"
                    className="components-button has-icon"
                    onClick={closeGuide}>
                    <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" size="24" role="img" aria-hidden="true" focusable="false"><path d="M13 11.8l6.1-6.3-1-1-6.1 6.2-6.1-6.2-1 1 6.1 6.3-6.5 6.7 1 1 6.5-6.6 6.5 6.6 1-1z"></path></svg>
                    <span className="sr-only">{__('Close library', 'extendify-sdk')}</span>
                </button>
            </div>
        </div>
    </div>

    return <div className="w-full h-full flex flex-col items-center relative shadow-xl max-w-screen-4xl mx-auto bg-white">
        {emptyToolbar}
        <section className="flex-grow w-full justify-between flex flex-col overflow-y-scroll">
            <div className="flex flex-col w-full lg:h-full max-w-screen-2xl mx-auto p-8" style={{ paddingTop: '13vh' }}>
                <h1 className="text-left m-0 mb-8 2xl:mb-16 text-7xl">
                    {__('Hello', 'extendify-sdk')}
                </h1>
                <div className="flex-grow flex flex-col lg:flex-row space-x-0 space-y-16 lg:space-y-0 lg:space-x-16 xl:space-x-32">
                    <button
                        style={{ maxHeight: '26rem' }}
                        ref={patternsRef}
                        onClick={() => setTypeAndProgress('pattern')}
                        className="button-focus-big-green cursor-pointer bg-white border border-black flex xl:w-1/2 flex-col h-full hover:bg-gray-50 min-h-60 p-8 lg:px-20 space-y-4">
                        <h3 className="text-2xl m-0 text-extendify-main">{__('Add a section', 'extendify-sdk')}</h3>
                        <p className="text-left mb-8 text-lg">{__('Add to an existing page or build your own using patterns.', 'extendify-sdk')}</p>
                        <span className="pt-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="206" height="122" viewBox="0 0 206 122" fill="none">
                                <path d="M69 0H0V59H69V0Z" fill="#DFDFDF"/>
                                <path d="M204 0H79V60H204V0Z" fill="#DFDFDF"/>
                                <path d="M62.166 25H9.16602V28H62.166V25Z" fill="#F9F9F9"/>
                                <path d="M63.166 18H10.166V21H63.166V18Z" fill="#F9F9F9"/>
                                <path d="M62.166 34H9.16602V39H62.166V34Z" fill="#F9F9F9"/>
                                <path d="M62.166 43H9.16602V48H62.166V43Z" fill="#F9F9F9"/>
                                <path d="M140.166 25H87.166V28H140.166V25Z" fill="#F9F9F9"/>
                                <path d="M140.166 34H87.166V39H140.166V34Z" fill="#F9F9F9"/>
                                <path d="M140.166 43H87.166V48H140.166V43Z" fill="#F9F9F9"/>
                                <path d="M197.166 25H151.166V28H197.166V25Z" fill="#F9F9F9"/>
                                <path d="M141.166 17H88.166V20H141.166V17Z" fill="#F9F9F9"/>
                                <path d="M198.166 17H152.166V20H198.166V17Z" fill="#F9F9F9"/>
                                <path d="M62.166 10H9.16602V13H62.166V10Z" fill="#F9F9F9"/>
                                <path d="M140.166 9H87.166V12H140.166V9Z" fill="#F9F9F9"/>
                                <path d="M197.166 9H151.166V12H197.166V9Z" fill="#F9F9F9"/>
                                <path d="M197.166 34H151.166V39H197.166V34Z" fill="#F9F9F9"/>
                                <path d="M197.166 43H151.166V48H197.166V43Z" fill="#F9F9F9"/>
                                <path d="M154.172 77.8088H0V121.216H154.172V77.8088Z" fill="#DFDFDF"/>
                                <path d="M133.637 110.446C141.077 110.446 147.109 104.75 147.109 97.7229C147.109 90.6963 141.077 85 133.637 85C126.197 85 120.166 90.6963 120.166 97.7229C120.166 104.75 126.197 110.446 133.637 110.446Z" fill="#F9F9F9"/>
                                <path d="M205.166 78H162.166V121H205.166V78Z" fill="#DFDFDF"/>
                                <path d="M183.803 111.637C191.243 111.637 197.275 105.941 197.275 98.9141C197.275 91.8874 191.243 86.1912 183.803 86.1912C176.363 86.1912 170.332 91.8874 170.332 98.9141C170.332 105.941 176.363 111.637 183.803 111.637Z" fill="#F9F9F9"/>
                                <path d="M113.695 88.7898H13.4082V100.764H113.695V88.7898Z" fill="#F9F9F9"/>
                                <path d="M113.695 105.255H13.4082V109.745H113.695V105.255Z" fill="#F9F9F9"/>
                            </svg>
                        </span>
                    </button>
                    <button
                        style={{
                            maxHeight: '26rem',
                        }}
                        ref={templatesRef}
                        onClick={() => setTypeAndProgress('template')}
                        className="button-focus-big-green cursor-pointer bg-white border border-black flex xl:w-1/2 flex-col h-full hover:bg-gray-50 min-h-60 p-8 lg:px-20 space-y-4">
                        <h3 className="text-2xl m-0 text-extendify-main">{__('Add a page', 'extendify-sdk')}</h3>
                        <p className="text-left mb-8 text-lg">{__('Use a full page template that you can customize to make your own.', 'extendify-sdk')}</p>
                        <span className="pt-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="156" height="128" viewBox="0 0 156 128" fill="none">
                                <path d="M155.006 38.4395H0.833984V81.8471H155.006V38.4395Z" fill="#DFDFDF"/>
                                <path d="M155 0H1V36H155V0Z" fill="#DFDFDF"/>
                                <path d="M148 7H10V28H148V7Z" fill="#F9F9F9"/>
                                <path d="M147.521 47.4204H9.81445V50.414H147.521V47.4204Z" fill="#F9F9F9"/>
                                <path d="M147.521 56.4012H9.81445V60.8917H147.521V56.4012Z" fill="#F9F9F9"/>
                                <path d="M147.521 65.3821H9.81445V69.8726H147.521V65.3821Z" fill="#F9F9F9"/>
                                <path d="M155.006 83.8089H0.833984V127.217H155.006V83.8089Z" fill="#DFDFDF"/>
                                <path d="M21.7897 118.236C29.2297 118.236 35.261 112.539 35.261 105.513C35.261 98.486 29.2297 92.7898 21.7897 92.7898C14.3497 92.7898 8.31836 98.486 8.31836 105.513C8.31836 112.539 14.3497 118.236 21.7897 118.236Z" fill="#F9F9F9"/>
                                <path d="M144.529 92.7898H44.2422V104.764H144.529V92.7898Z" fill="#F9F9F9"/>
                                <path d="M144.529 109.255H44.2422V113.745H144.529V109.255Z" fill="#F9F9F9"/>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>
            <footer className="flex justify-between p-14 w-full">
                <button
                    type="button"
                    className="cursor-pointer bg-transparent space-x-8 flex items-center hover:bg-gray-100 p-4 -m-4"
                    onClick={goToWelcome}>
                    <svg className="block" width="64" height="64" viewBox="0 0 103 103" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect y="25.75" width="70.8125" height="77.25" fill="#000000"/>
                        <rect x="45.0625" width="57.9375" height="57.9375" fill="#37C2A2"/>
                    </svg>
                    <span className="text-2xl font-bold text-extendify-main">
                        {__('What is Extendify?', 'extendify-sdk')}
                    </span>
                </button>
                {/* <button
                    type="button"
                    onClick={closeGuide}
                    className="bg-transparent cursor-pointer text-lg text-extendify-link underline">
                    {__('Skip', 'extendify-sdk')}
                </button> */}
            </footer>
        </section>
    </div>
}
