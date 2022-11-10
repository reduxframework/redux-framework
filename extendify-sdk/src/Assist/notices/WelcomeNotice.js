import { useState, useEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { Transition } from '@headlessui/react'
import { motion } from 'framer-motion'
import { useAdminColors } from '@assist/hooks/useAdminColors'
import { useGlobalStore, useGlobalStoreReady } from '@assist/state/Global'

const noticeKey = 'welcome-message'
export const WelcomeNotice = () => {
    const [iFrameSettled, setIFrameSettled] = useState(false)
    const [iFrameInView, setIFrameInView] = useState(false)
    const [iFrameCentered, setIFrameCentered] = useState(false)
    const [contentReady, setContentReady] = useState(false)
    const { isDismissed, dismissNotice } = useGlobalStore()
    const { mainColor } = useAdminColors()
    const url = window.extAssistData.home + '?extendify-disable-admin-bar'
    const ready = useGlobalStoreReady()
    // To avoid content flash, we load in this partial piece of state early via php
    const dismissed = window.extAssistData.dismissedNotices.find(
        (notice) => notice.id === noticeKey,
    )
    const [enabled, setEnabled] = useState(false)

    useEffect(() => {
        if (dismissed || isDismissed(noticeKey)) {
            return
        }
        setEnabled(true)
    }, [dismissed, isDismissed, dismissNotice])

    useEffect(() => {
        if (!enabled || !ready) return
        // For this notice, we only want to show it once
        dismissNotice(noticeKey)
    }, [dismissNotice, enabled, ready])

    if (!enabled) return null

    return (
        <motion.div
            layout
            className="hidden lg:block bg-black overflow-hidden"
            transition={{ duration: 0.5, delay: 1 }}
            initial={{ height: 0 }}
            animate={{ height: '600px', bgColor: 'black' }}>
            <div className="p-8 lg:p-24 xl:p-32">
                <button
                    className="bg-transparent text-white opacity-70 hover:opacity-100 border-0 shadow-none absolute top-0 right-0 p-4 cursor-pointer text-sm"
                    type="button"
                    onClick={() => setEnabled(false)}>
                    <span className="dashicons dashicons-no-alt"></span>
                    <span>{__('Dismiss', 'extendify')}</span>
                </button>
                <section className="md:flex relative justify-center max-w-screen-xl mx-auto gap-16">
                    <Transition
                        show={contentReady}
                        className="absolute inset-0 md:w-1/2">
                        <Transition.Child
                            enter="transition-all ease-in-out duration-500"
                            enterFrom="transform translate-y-14 opacity-0"
                            enterTo="transform translate-y-0 opacity-100">
                            <p className="relative inline-block font-bold m-0 mb-16 text-3xl text-white">
                                {__('Congratulations!', 'extendify')}
                                <span className="block absolute text-sm right-0 m-0 text-right text-gray-500">
                                    {__('Your site is ready.', 'extendify')}
                                </span>
                            </p>
                        </Transition.Child>
                        <Transition.Child
                            enter="transition-all ease-in-out duration-500 delay-300"
                            enterFrom="transform translate-y-14 opacity-0"
                            enterTo="transform translate-y-0 opacity-100">
                            <div className="flex flex-col gap-4">
                                <h1 className="m-0 text-4xl text-white translate-y-1">
                                    {__("What's Next?", 'extendify')}
                                </h1>
                                <p className="m-0 text-white text-base">
                                    {__(
                                        'This Assist dashboard ensures your business gets value from your website. Complete the following tasks to build a successful and highly functional website. You can view and edit the Launch created pages below.',
                                        'extendify',
                                    )}
                                </p>
                                <div>
                                    <a
                                        href={document.location.origin}
                                        target="_blank"
                                        rel="noreferrer"
                                        style={{ backgroundColor: mainColor }}
                                        className="inline-block cursor-pointer rounded-sm px-6 py-2 text-lg text-white border-none no-underline">
                                        {__('View site', 'extendify')}
                                    </a>
                                </div>
                            </div>
                        </Transition.Child>
                    </Transition>
                    <motion.div
                        className="flex justify-center flex-shrink-0 overflow-hidden relative pointer-events-none w-full"
                        initial={{ opacity: 0, transform: 'none' }}
                        animate={{
                            opacity: iFrameSettled ? 1 : 0.3,
                            transform: iFrameCentered
                                ? 'translateX(300px)'
                                : 'none',
                        }}
                        transition={{
                            duration: iFrameCentered ? 0.7 : 0.3,
                            ease: 'easeInOut',
                        }}
                        onAnimationComplete={() => {
                            window.requestAnimationFrame(() => {
                                setIFrameInView(true)
                                if (iFrameCentered) setContentReady(true)
                            })
                        }}>
                        {/* added to prevent mouse scroll */}
                        <div className="absolute z-10 inset-0 pointer-events-none" />
                        <motion.iframe
                            ref={(r) => {
                                const load = () =>
                                    window.requestAnimationFrame(() =>
                                        setIFrameSettled(true),
                                    )
                                if (!iFrameSettled) {
                                    r?.addEventListener('load', load)
                                    return
                                }
                                r?.removeEventListener('load', load)
                            }}
                            src={url}
                            style={{
                                aspectRatio: '600/800',
                                minWidth: '1200px',
                            }}
                            className="origin-top w-full"
                            initial={{ transform: 'scale(0.65) translateY(0)' }}
                            transition={{ delay: 2, duration: 0.5 }}
                            layout
                            onAnimationComplete={() => {
                                window.requestAnimationFrame(() => {
                                    setIFrameCentered(true)
                                })
                            }}
                            animate={{
                                transform: iFrameInView
                                    ? 'scale(0.24) translateY(-30px)'
                                    : 'scale(1)',
                            }}
                        />
                    </motion.div>
                </section>
            </div>
        </motion.div>
    )
}
