import { useUserStore } from '../../state/User'
import ImportCounter from '../../components/ImportCounter'

export default function HasSidebar({ children }) {
    const apiKey = useUserStore((state) => state.apiKey)
    return (
        <>
            <aside className="flex-shrink-0 sm:pl-8 py-0 relative">
                <div className="sm:w-56 lg:w-72 sticky flex flex-col lg:h-full">
                    {children[0]}
                </div>
                <div className="hidden sm:flex flex-col absolute bottom-0 mb-8 w-72 text-left space-y-4">
                    {!apiKey.length && <ImportCounter />}
                </div>
            </aside>
            <main
                id="extendify-templates"
                className="w-full smp:l-12 h-full overflow-hidden">
                {children[1]}
            </main>
        </>
    )
}
