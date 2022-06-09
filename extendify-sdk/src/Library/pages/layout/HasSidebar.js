import { useGlobalStore } from '@library/state/GlobalState'

export default function HasSidebar({ children }) {
    const ready = useGlobalStore((state) => state.ready)
    return (
        <>
            <aside className="relative flex-shrink-0 border-r border-extendify-transparent-black-100 bg-extendify-transparent-white py-0 backdrop-blur-xl backdrop-saturate-200 backdrop-filter sm:pt-5">
                <div className="flex h-full flex-col py-6 sm:w-72 sm:space-y-6 sm:py-0">
                    {ready ? children[0] : null}
                </div>
            </aside>
            <main
                id="extendify-templates"
                className="h-full w-full overflow-hidden bg-gray-50 pt-6 sm:pt-0">
                {ready ? children[1] : null}
            </main>
        </>
    )
}
