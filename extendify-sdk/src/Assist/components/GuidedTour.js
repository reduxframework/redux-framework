import { Button } from '@wordpress/components'
import {
    useRef,
    useCallback,
    useEffect,
    useLayoutEffect,
    useState,
    useMemo,
} from '@wordpress/element'
import { sprintf, __ } from '@wordpress/i18n'
import { Icon, close } from '@wordpress/icons'
import { Dialog } from '@headlessui/react'
import { motion, AnimatePresence } from 'framer-motion'
import { useDesignColors } from '@assist/hooks/useDesignColors'
import { useGlobalSyncStore } from '@assist/state/GlobalSync'
import { useTasksStore } from '@assist/state/Tasks'
import { useTourStore } from '@assist/state/Tours'
import availableTours from '@assist/tours/tours.js'

const getBoundingClientRect = (element) => {
    const { top, right, bottom, left, width, height, x, y } =
        element.getBoundingClientRect()
    return { top, right, bottom, left, width, height, x, y }
}

export const GuidedTour = () => {
    const tourBoxRef = useRef()
    const {
        currentTour,
        currentStep,
        startTour,
        closeCurrentTourManually,
        closeForRedirect,
        getStepData,
    } = useTourStore()
    const { settings } = currentTour || {}
    const { image, title, text, attachTo, events, options } =
        getStepData(currentStep)

    const { queueTourForRedirect, queuedTour, clearQueuedTour } =
        useGlobalSyncStore()
    const { element, offset, position, hook, boxPadding } = attachTo || {}
    const elementSelector = useMemo(
        () => (typeof element === 'function' ? element() : element),
        [element],
    )
    const offsetNormalized = useMemo(
        () => (typeof offset === 'function' ? offset() : offset),
        [offset],
    )
    const hookNormalized = useMemo(
        () => (typeof hook === 'function' ? hook() : hook),
        [hook],
    )

    const [targetedElement, setTargetedElement] = useState(null)

    const initialFocus = useRef()
    const [redirecting, setRedirecting] = useState(false)
    const [visible, setVisible] = useState(false)

    const [overlayRect, setOverlayRect] = useState(null)

    const [placement, setPlacement] = useState({
        x: undefined,
        y: undefined,
        ...offsetNormalized,
    })
    const setTourBox = useCallback(
        (x, y) => {
            // x is 20 on mobile, so exclude the offset here
            setPlacement(x === 20 ? { x, y } : { x, y, ...offsetNormalized })
        },
        [offsetNormalized],
    )
    const getOffset = useCallback(() => {
        const hooks = hookNormalized?.split(' ') || []
        return {
            x: hooks.includes('right') ? tourBoxRef.current?.offsetWidth : 0,
            y: hooks.includes('bottom') ? tourBoxRef.current?.offsetHeight : 0,
        }
    }, [hookNormalized])

    const startOrRecalc = useCallback(() => {
        if (!targetedElement) return
        const rect = getBoundingClientRect(
            document.querySelector(elementSelector) ?? targetedElement,
        )
        // Just put near the top if on smaller screens
        // (960 is when the admin bar collapses)
        if (window.innerWidth <= 960) {
            return setTourBox(20, 20)
        }
        if (position?.x === undefined) {
            setTourBox(undefined, undefined)
            setOverlayRect(null)
            setVisible(false)
            return
        }
        const x = rect?.[position.x] - getOffset().x
        const y = rect?.[position.y] - getOffset().y
        const box = tourBoxRef.current
        // make sure it doesn't go off screen
        setTourBox(
            Math.min(x, window.innerWidth - (box?.offsetWidth ?? 0) - 20),
            Math.min(y, window.innerHeight - (box?.offsetHeight ?? 0) - 20),
        )
        setOverlayRect(rect)
    }, [targetedElement, position, getOffset, setTourBox, elementSelector])

    // Pre launch check whether to redirect
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

    // Possibly start the tour, or wait for the load event
    useLayoutEffect(() => {
        if (redirecting) return
        const tour = queuedTour
        let rafId = 0
        if (!tour || !availableTours[tour]) return clearQueuedTour()
        const handle = () => {
            requestAnimationFrame(() => {
                startTour(availableTours[tour])
            })
            clearQueuedTour()
        }

        addEventListener('load', handle)
        if (document.readyState === 'complete') {
            // Page is already loaded, so we can start the tour immediately
            rafId = requestAnimationFrame(handle)
        }
        return () => {
            cancelAnimationFrame(rafId)
            removeEventListener('load', handle)
        }
    }, [startTour, queuedTour, clearQueuedTour, redirecting])

    useEffect(() => {
        // Find an set the element we are attaching to
        const element = document.querySelector(elementSelector)
        if (!element) return
        setTargetedElement(element)
        return () => setTargetedElement(null)
    }, [elementSelector])

    // Start building the tour step
    useLayoutEffect(() => {
        if (!targetedElement || redirecting) return
        setVisible(true)
        startOrRecalc()
        addEventListener('resize', startOrRecalc)
        targetedElement.style.pointerEvents = 'none'
        return () => {
            removeEventListener('resize', startOrRecalc)
            targetedElement.style.pointerEvents = 'auto'
        }
    }, [redirecting, targetedElement, startOrRecalc])

    // Handle the attach and detach events
    useEffect(() => {
        if (currentStep === undefined || !targetedElement) return
        events?.onAttach?.(targetedElement)
        let inner = 0
        const id = requestAnimationFrame(() => {
            inner = requestAnimationFrame(startOrRecalc)
        })
        return () => {
            events?.onDetach?.(targetedElement)
            cancelAnimationFrame(id)
            cancelAnimationFrame(inner)
        }
    }, [currentStep, events, targetedElement, startOrRecalc])

    useLayoutEffect(() => {
        if (!settings?.allowOverflow) return
        document.documentElement.classList.add('ext-force-overflow-auto')
        return () => {
            document.documentElement.classList.remove('ext-force-overflow-auto')
        }
    }, [settings])

    if (!visible) return null

    const rectWithPadding = addPaddingToRect(overlayRect, boxPadding)
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
                        <div className="relative z-max">
                            <motion.div
                                ref={tourBoxRef}
                                animate={{ opacity: 1, ...placement }}
                                initial={{ opacity: 0, ...placement }}
                                // TODO: fire another event after animation completes?
                                onAnimationComplete={() => {
                                    startOrRecalc()
                                }}
                                transition={{
                                    duration: 0.5,
                                    ease: 'easeInOut',
                                }}
                                className="fixed top-0 left-0 shadow-2xl sm:overflow-hidden bg-transparent flex flex-col max-w-xs z-20"
                                style={{ minWidth: '325px' }}>
                                <button
                                    className="absolute bg-white cursor-pointer flex ring-gray-200 ring-1 focus:ring-wp focus:ring-design-main focus:shadow-none h-6 items-center justify-center leading-none m-2 outline-none p-0 right-0 rounded-full top-0 w-6 border-0 z-20"
                                    onClick={closeCurrentTourManually}
                                    aria-label={__('Close Modal', 'extendify')}>
                                    <Icon icon={close} className="w-4 h-4" />
                                </button>
                                <Dialog.Title className="sr-only">
                                    {currentTour?.title ??
                                        __('Tour', 'extendify')}
                                </Dialog.Title>
                                {image && (
                                    <div
                                        className="w-full p-6"
                                        style={{
                                            minHeight: 150,
                                            background:
                                                'linear-gradient(58.72deg, #485563 7.71%, #29323C 92.87%)',
                                        }}>
                                        <img
                                            src={image}
                                            className="w-full block"
                                            alt={title}
                                        />
                                    </div>
                                )}
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
            {options?.blockPointerEvents && (
                <div aria-hidden={true} className="fixed inset-0 z-max-1" />
            )}
            <AnimatePresence>
                {Boolean(currentTour) && overlayRect?.left !== undefined && (
                    <>
                        <motion.div
                            initial={{
                                opacity: 0,
                                clipPath:
                                    'polygon(0px 0px, 100% 0px, 100% 100%, 0px 100%, 0 0)',
                            }}
                            animate={{
                                opacity: 1,
                                clipPath: `polygon(0px 0px, 100% 0px, 100% 100%, 0px 100%, 0 0, ${rectWithPadding.left}px 0, ${rectWithPadding.left}px ${rectWithPadding?.bottom}px, ${rectWithPadding?.right}px ${rectWithPadding.bottom}px, ${rectWithPadding.right}px ${rectWithPadding.top}px, ${rectWithPadding.left}px ${rectWithPadding.top}px)`,
                            }}
                            transition={{ duration: 0.5, ease: 'easeInOut' }}
                            className="hidden lg:block fixed inset-0 bg-black bg-opacity-70 z-high"
                            aria-hidden="true"
                        />
                        <motion.div
                            initial={{
                                opacity: 0,
                                ...(rectWithPadding ?? {}),
                            }}
                            animate={{
                                opacity: 1,
                                ...(rectWithPadding ?? {}),
                            }}
                            transition={{ duration: 0.5, ease: 'easeInOut' }}
                            className="hidden lg:block fixed inset-0 border-2 border-design-main z-high"
                            aria-hidden="true"
                        />
                    </>
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
                        id="assist-tour-next-button"
                        data-test="assist-tour-next-button"
                        onClick={nextStep}
                        style={{
                            backgroundColor: mainColor,
                        }}
                        variant="primary">
                        {__('Next', 'extendify')}
                    </Button>
                ) : (
                    <Button
                        id="assist-tour-next-button"
                        data-test="assist-tour-next-button"
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

const addPaddingToRect = (rect, padding) => ({
    top: rect.top - (padding?.top ?? 0),
    left: rect.left - (padding?.left ?? 0),
    right: rect.right + (padding?.right ?? 0),
    bottom: rect.bottom + (padding?.bottom ?? 0),
    width: rect.width + (padding?.left ?? 0) + (padding?.right ?? 0),
    height: rect.height + (padding?.top ?? 0) + (padding?.bottom ?? 0),
    x: rect.x - (padding?.left ?? 0),
    y: rect.y - (padding?.top ?? 0),
})
