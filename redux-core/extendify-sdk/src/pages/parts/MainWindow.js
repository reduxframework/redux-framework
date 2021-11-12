import {
    Fragment, useRef, useEffect,
} from '@wordpress/element'
import { Dialog, Transition } from '@headlessui/react'
import useBeacon from '../../hooks/useBeacon'
import { useGlobalStore } from '../../state/GlobalState'
import Router from '../Router'
import useTaxonomies from '../../hooks/useTaxonomies'
import { General as GeneralApi } from '../../api/General'
import { useUserStore } from '../../state/User'

export default function MainWindow() {
    const containerRef = useRef(null)
    const open = useGlobalStore(state => state.open)
    const metaData = useGlobalStore(state => state.metaData)
    const currentPage = useGlobalStore(state => state.currentPage)
    useBeacon(open)
    useTaxonomies(open)

    useEffect(() => {
        if (!open) return
        if (!useUserStore.getState().hasClickedThroughWelcomePage) {
            useGlobalStore.setState({ currentPage: 'welcome' })
            return
        }
        // if (!window.sessionStorage.getItem('esxtendify-show-guide')) {
        //     window.sessionStorage.setItem('esxtendify-show-guide', '1')
        //     useGlobalStore.setState({ currentPage: 'guide-start' })
        //     return
        // }
    }, [open])

    useEffect(() => {
        if (!open || Object.keys(metaData).length) {
            return
        }
        GeneralApi.metaData().then((data) => useGlobalStore.setState({ metaData: data }))
    }, [open, metaData])

    return (
        <Transition.Root show={open} as={Fragment}>
            <Dialog
                as="div"
                static
                className="extendify-sdk"
                initialFocus={containerRef}
                onClose={() => {}}
            >
                <div className="h-screen w-screen sm:h-auto sm:w-auto fixed z-high inset-0 overflow-y-auto">
                    <div className="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <Transition.Child
                            as={Fragment}
                            enter="ease-out duration-300"
                            enterFrom="opacity-0"
                            enterTo="opacity-100"
                        >
                            <Dialog.Overlay className="fixed inset-0 bg-black bg-opacity-30 transition-opacity" />
                        </Transition.Child>
                        <Transition.Child
                            as={Fragment}
                            enter="ease-out duration-300"
                            enterFrom="opacity-0 translate-y-4 sm:translate-y-5"
                            enterTo="opacity-100 translate-y-0"
                        >
                            <div
                                ref={containerRef}
                                tabIndex="0"
                                className="fixed lg:absolute inset-0 lg:overflow-hidden transform transition-all lg:p-5">
                                <Router page={currentPage} />
                            </div>
                        </Transition.Child>
                    </div>
                </div>
            </Dialog>
        </Transition.Root>
    )
}
