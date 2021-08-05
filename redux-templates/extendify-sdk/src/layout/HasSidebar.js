import LoginButton from '../components/Loginbutton'
import { useUserStore } from '../state/User'
import { useState, useEffect } from '@wordpress/element'

export default function HasSidebar({ children }) {
    const apiKey = useUserStore(state => state.apiKey)
    const [canLogInOut, setCanInLogOut] = useState(false)
    useEffect(() => {
        setCanInLogOut(!apiKey.length || window.location.search.indexOf('DEVMODE') > -1)
    }, [apiKey])
    return <>
        <aside className="flex-shrink-0 sm:pl-12 py-0 sm:py-6 relative">
            <div className="sm:w-56 lg:w-72 sticky flex flex-col lg:h-full">{children[0]}</div>
            <div className="hidden sm:flex flex-col absolute bottom-0 bg-white p-4 w-72 text-left space-y-4">
                {canLogInOut && <div className="border-t border-gray-300"><LoginButton/></div>}
            </div>
        </aside>
        <main
            id="extendify-templates"
            tabIndex="0"
            className="w-full smp:l-12 sm:pt-6 h-full overflow-hidden">
            {children[1]}
        </main>
    </>
}
