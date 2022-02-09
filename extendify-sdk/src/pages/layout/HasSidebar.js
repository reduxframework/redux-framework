export default function HasSidebar({ children }) {
    return (
        <>
            <aside className="flex-shrink-0 py-0 sm:py-5 relative border-r border-extendify-transparent-black-100 bg-extendify-transparent-white backdrop-filter backdrop-blur-xl backdrop-saturate-200">
                <div className="flex flex-col h-full sm:w-64 py-6 sm:py-0 sm:space-y-6">
                    {children[0]}
                </div>
            </aside>
            <main
                id="extendify-templates"
                className="bg-gray-50 w-full pt-6 sm:pt-0 h-full overflow-hidden">
                {children[1]}
            </main>
        </>
    )
}
