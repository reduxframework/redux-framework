import { useUserStore } from '../../state/User'
import ImportCounter from '../../components/ImportCounter'

export default function HasSidebar({ children }) {
    const apiKey = useUserStore(state => state.apiKey)
    return <>
        <aside className="flex-shrink-0 sm:pl-6 py-0 sm:py-6 relative">
            <div className="sm:w-56 lg:w-64 sticky flex flex-col lg:h-full">{children[0]}</div>
            <div className="hidden sm:flex flex-col absolute bottom-0 mb-6 w-64 text-left space-y-4">
                { !apiKey.length && <ImportCounter /> }
            </div>
        </aside>
        <main
            id="extendify-templates"
            className="w-full smp:l-12 sm:pt-6 h-full overflow-hidden">
            {children[1]}
        </main>
    </>
}
