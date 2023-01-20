import { Button } from '@wordpress/components'
import {
    useRef,
    useEffect,
    useLayoutEffect,
    useState,
} from '@wordpress/element'
import { sprintf, __ } from '@wordpress/i18n'
import { Icon, close } from '@wordpress/icons'
import { Dialog } from '@headlessui/react'
import { motion, AnimatePresence } from 'framer-motion'
import useSWRImmutable from 'swr/immutable'
import { useDesignColors } from '@assist/hooks/useDesignColors'
import { useGlobalSyncStore } from '@assist/state/GlobalSync'
import { useTasksStore } from '@assist/state/Tasks'
import { useTourStore } from '@assist/state/Tours'
import welcomeTour from '@assist/tours/welcome.js'
import { copyNodeStyle } from '@assist/util/element'

const useClonedElement = ({ elementSelector, key, options }) => {
    const { data: clonedNode } = useSWRImmutable(key, () => {
        const currentElement = document.querySelector(elementSelector)
        if (!currentElement) return null
        // Clone currentElement then place the new element fixed in the same position
        // as the original element. This allows us to use the original element as a
        // reference for positioning the tour modal.
        const clonedElement = currentElement?.cloneNode(true)
        // copy over computed styles, walking down - mutates the node directly
        copyNodeStyle(currentElement, clonedElement, options)
        // add to the DOM with max z-index
        clonedElement.style.position = 'fixed'
        clonedElement.style.zIndex = 999999
        return clonedElement
    })
    return clonedNode
}

const availableTours = {
    [welcomeTour.id]: welcomeTour,
}

export const GuidedTour = () => {
    const tourModalRef = useRef()
    const {
        currentTour,
        currentStep,
        startTour,
        closeCurrentTourManually,
        closeCurrentTourFromError,
        closeForRedirect,
    } = useTourStore()
    const { steps, settings } = currentTour || {}
    const { image, title, text, attachTo, events, cloneOptions } =
        steps?.[currentStep] ?? {}
    const { queueTourForRedirect, queuedTour, clearQueuedTour } =
        useGlobalSyncStore()
    const [redirecting, setRedirecting] = useState(false)
    const { onAttach, onDetach, beforeAttach } = events || {}
    const { element: elementSelector, offset, position, hook } = attachTo || {}
    const [attachToElement, setAttachToElement] = useState(null)
    const initialFocus = useRef()
    const [x, setX] = useState()
    const [y, setY] = useState()
    // x is 20 on mobile, so exclude the offset here
    const placement = x === 20 ? { x, y } : { x, y, ...offset }
    const clonedElement = useClonedElement({
        elementSelector,
        key: { elementSelector, x },
        options: cloneOptions,
    })

    useEffect(() => {
        if (redirecting) return
        const tour = queuedTour
        if (!tour || !availableTours[tour]) return
        clearQueuedTour()
        return () =>
            requestAnimationFrame(() => startTour(availableTours[tour]))
    }, [startTour, queuedTour, redirecting, clearQueuedTour])

    useLayoutEffect(() => {
        // if the tour has a start from url, redirect there
        if (!settings?.startFrom) return
        if (window.location.href === settings.startFrom) return
        if (
            // if only hash changed, update the url only
            window.location.href.split('#')[0] ===
            settings.startFrom.split('#')[0]
        ) {
            window.location.assign(settings?.startFrom)
            return
        }
        setRedirecting(true)
        queueTourForRedirect(currentTour.id)
        closeForRedirect()
        window.location.assign(settings?.startFrom)
    }, [
        settings?.startFrom,
        currentTour,
        queueTourForRedirect,
        closeForRedirect,
    ])

    useLayoutEffect(() => {
        if (!currentTour || redirecting) return
        const currentElement = document.querySelector(elementSelector)
        if (!currentElement) {
            // TODO: error message? snackbar?
            closeCurrentTourFromError()
            return
        }
        beforeAttach && beforeAttach(clonedElement)
        setAttachToElement(currentElement)

        // Cache so we can access it in render callback
        const previouslyCloned = clonedElement

        const model = tourModalRef.current
        const offset = () => {
            const hooks = hook?.split(' ') || []
            return {
                x: hooks.includes('right') ? model?.offsetWidth : 0,
                y: hooks.includes('bottom') ? model?.offsetHeight : 0,
            }
        }
        const reset = () => {
            if (onDetach && previouslyCloned) onDetach(previouslyCloned)
            if (previouslyCloned) document.body.removeChild(previouslyCloned)
            setAttachToElement(null)
            removeEventListener('resize', measure)
            setX(null)
            setY(null)
        }
        // Measure the position of the element and set the position of the modal nearby
        const measure = () => {
            const currentElementRect = currentElement?.getBoundingClientRect()
            const windowSize = window.innerWidth
            // Just put near the top if on smaller screens
            // (960 is when the admin bar collapses)
            if (windowSize <= 960) {
                setX(20)
                setY(20)
                const id = requestAnimationFrame(() => {
                    if (clonedElement?.parentNode) {
                        // set opacity to 0
                        clonedElement.style.opacity = 0
                    }
                })
                return () => {
                    cancelAnimationFrame(id)
                    reset()
                }
            }

            // Happy path - animate to the position
            setX(currentElementRect?.[position.x] - offset().x)
            setY(currentElementRect?.[position.y] - offset().y)

            if (!clonedElement) return
            // Position the clone right above the source
            clonedElement.style.top = `${currentElementRect?.top}px`
            clonedElement.style.left = `${currentElementRect?.left}px`
            clonedElement.style.opacity = 1
        }
        measure()

        if (!clonedElement) return reset
        if (onAttach) onAttach(clonedElement)
        document.body.appendChild(clonedElement)
        addEventListener('resize', measure)
        return reset
    }, [
        closeCurrentTourFromError,
        clonedElement,
        currentStep,
        currentTour,
        elementSelector,
        hook,
        position,
        beforeAttach,
        onAttach,
        onDetach,
        redirecting,
    ])

    useLayoutEffect(() => {
        if (!settings?.allowOverflow) return
        // TODO: Should this be an option? We may need to scroll
        document.documentElement.classList.add('ext-force-overflow-auto')
        return () => {
            document.documentElement.classList.remove('ext-force-overflow-auto')
        }
    }, [settings])

    if (!attachToElement) return null

    return (
        <>
            <AnimatePresence>
                {Boolean(currentTour) && (
                    <Dialog
                        as={motion.div}
                        static
                        initialFocus={initialFocus}
                        className="extendify-assist"
                        open={Boolean(currentTour)}
                        onClose={() => undefined}>
                        <div className="relative z-high">
                            <motion.div
                                ref={tourModalRef}
                                initial={{ opacity: 0, ...placement }}
                                animate={{ opacity: 1, ...placement }}
                                transition={{
                                    duration: 0.5,
                                    ease: 'easeInOut',
                                }}
                                className="fixed top-0 left-0 shadow-2xl sm:overflow-hidden bg-transparent flex flex-col min-h-60 max-w-xs z-20"
                                style={{ minWidth: '325px' }}>
                                <button
                                    className="absolute bg-white cursor-pointer flex ring-gray ring-1 focus:ring-wp focus:ring-design-main focus:shadow-none h-6 items-center justify-center leading-none m-2 outline-none p-0 right-0 rounded-full top-0 w-6 border-0 z-20"
                                    onClick={closeCurrentTourManually}
                                    aria-label={__('Close Modal', 'extendify')}>
                                    <Icon icon={close} className="w-4 h-4" />
                                </button>
                                <Dialog.Title className="sr-only">
                                    {currentTour?.title ??
                                        __('Tour', 'extendify')}
                                </Dialog.Title>
                                <div
                                    className="w-full p-6"
                                    style={{
                                        background:
                                            'linear-gradient(58.72deg, #485563 7.71%, #29323C 92.87%)',
                                    }}>
                                    {image && (
                                        <img
                                            src={image}
                                            className="w-full block"
                                            alt={title}
                                        />
                                    )}
                                </div>
                                <div className="m-0 p-6 pt-0 text-left relative bg-white">
                                    {title && (
                                        <h2 className="text-xl font-medium mb-2">
                                            {title}
                                        </h2>
                                    )}
                                    {text && <p className="mb-6">{text}</p>}
                                    <BottomNav initialFocus={initialFocus} />
                                </div>
                            </motion.div>
                        </div>
                    </Dialog>
                )}
            </AnimatePresence>
            <AnimatePresence>
                {Boolean(currentTour) && (
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        transition={{ duration: 0.1, ease: 'easeIn' }}
                        className="fixed inset-0 bg-black bg-opacity-60 transition-opacity z-high"
                        aria-hidden="true"
                    />
                )}
            </AnimatePresence>
        </>
    )
}

const BottomNav = ({ initialFocus }) => {
    const {
        goToStep,
        completeCurrentTour,
        currentStep,
        nextStep,
        hasNextStep,
        hasPreviousStep,
        prevStep,
    } = useTourStore()
    const { currentTour } = useTourStore()
    const { id, steps } = currentTour || {}
    const { mainColor } = useDesignColors()
    const { completeTask } = useTasksStore()
    return (
        <div className="flex justify-between items-center w-full">
            <div className="flex-1 flex justify-start">
                <AnimatePresence>
                    {hasPreviousStep() ? (
                        <motion.div
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}>
                            <button
                                className="flex p-0 h-8 rounded-sm items-center justify-center bg-transparent hover:bg-transparent focus:outline-none ring-design-main focus:ring-wp focus:ring-offset-1 focus:ring-offset-white text-gray-900"
                                onClick={prevStep}>
                                {__('Back', 'extendify')}
                            </button>
                        </motion.div>
                    ) : null}
                </AnimatePresence>
            </div>
            {steps?.length > 2 ? (
                <nav
                    role="navigation"
                    aria-label={__('Tour Steps', 'extendify')}
                    className="flex-1 flex items-center justify-center gap-1.5 transform -translate-x-3">
                    {steps.map((_step, index) => (
                        <div key={index}>
                            <button
                                style={{
                                    backgroundColor:
                                        index === currentStep
                                            ? mainColor
                                            : undefined,
                                }}
                                aria-label={sprintf(
                                    __('%s of %s', 'extendify'),
                                    index + 1,
                                    steps.length,
                                )}
                                aria-current={index === currentStep}
                                className={`focus:ring-wp focus:outline-none ring-offset-1 ring-offset-white focus:ring-design-main block cursor-pointer w-2.5 h-2.5 m-0 p-0 rounded-full ${
                                    index === currentStep
                                        ? 'bg-design-main'
                                        : 'bg-gray-300'
                                }`}
                                onClick={() => goToStep(index)}
                            />
                        </div>
                    ))}
                </nav>
            ) : null}

            <div className="flex-1 flex justify-end">
                {hasNextStep() ? (
                    <Button
                        ref={initialFocus}
                        onClick={nextStep}
                        style={{
                            backgroundColor: mainColor,
                        }}
                        variant="primary">
                        {__('Next', 'extendify')}
                    </Button>
                ) : (
                    <Button
                        onClick={() => {
                            completeTask(id)
                            completeCurrentTour()
                        }}
                        style={{
                            backgroundColor: mainColor,
                        }}
                        variant="primary">
                        {__('Done', 'extendify')}
                    </Button>
                )}
            </div>
        </div>
    )
}
