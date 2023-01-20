import { useEffect, useState, useCallback } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { Transition } from '@headlessui/react'
import { colord } from 'colord'
import {
    installPlugin,
    updateTemplatePart,
    addLaunchPagesToNav,
    updateOption,
    getOption,
    getPageById,
    getActivePlugins,
} from '@onboarding/api/WPApi'
import { useConfetti } from '@onboarding/hooks/useConfetti'
import { useFetch } from '@onboarding/hooks/useFetch'
import { useWarnOnLeave } from '@onboarding/hooks/useWarnOnLeave'
import { waitFor200Response } from '@onboarding/lib/util'
import {
    createWordpressPages,
    trashWordpressPages,
    updateGlobalStyleVariant,
} from '@onboarding/lib/wp'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { Logo, Spinner } from '@onboarding/svg'

export const CreatingSite = () => {
    const [isShowing] = useState(true)
    const [confettiReady, setConfettiReady] = useState(false)
    const [confettiColors, setConfettiColors] = useState(['#ffffff'])
    const [warnOnLeaveReady, setWarnOnLeaveReady] = useState(true)
    const canLaunch = useUserSelectionStore((state) => state.canLaunch())
    const { data: activePlugins } = useFetch('active-plugins', getActivePlugins)
    const {
        siteType,
        siteInformation,
        pages,
        style,
        variation,
        plugins,
        goals,
    } = useUserSelectionStore()
    const [info, setInfo] = useState([])
    const [infoDesc, setInfoDesc] = useState([])
    const inform = (msg) => setInfo((info) => [msg, ...info])
    const informDesc = (msg) => setInfoDesc((infoDesc) => [msg, ...infoDesc])

    useWarnOnLeave(warnOnLeaveReady)

    const doEverything = useCallback(async () => {
        if (!canLaunch) {
            throw new Error(__('Site is not ready to launch.', 'extendify'))
        }
        try {
            inform(__('Applying site styles', 'extendify'))
            informDesc(__('A beautiful site in... 3, 2, 1', 'extendify'))
            await new Promise((resolve) => setTimeout(resolve, 1000))

            await waitFor200Response()
            await updateOption('blogname', siteInformation.title)

            await waitFor200Response()
            await updateGlobalStyleVariant(variation ?? {})

            await waitFor200Response()
            await updateTemplatePart('extendable/header', style?.headerCode)

            await waitFor200Response()
            await updateTemplatePart('extendable/footer', style?.footerCode)

            if (plugins?.length) {
                inform(__('Installing suggested plugins', 'extendify'))
                for (const [index, plugin] of plugins.entries()) {
                    // TODO: instead of updating here, we could have a progress component
                    // that we can update a % of the width every index/n
                    informDesc(
                        __(
                            `${index + 1}/${plugins.length}: ${plugin.name}`,
                            'extendify',
                        ),
                    )
                    await waitFor200Response()
                    try {
                        await installPlugin(plugin)
                    } catch (e) {
                        // If this fails, wait and try again
                        await waitFor200Response()
                        await installPlugin(plugin)
                    }
                }
            }

            let pageIds, navPages
            inform(__('Generating page content', 'extendify'))
            informDesc(__('Starting off with a full site...', 'extendify'))
            await new Promise((resolve) => setTimeout(resolve, 1000))
            await waitFor200Response()

            const blogPage = {
                // slug is only used internally
                slug: 'blog',
                title: __('Blog', 'extendify'),
            }
            const pagesWithBlog = [...pages, blogPage]
            await waitFor200Response()
            pageIds = await createWordpressPages(pagesWithBlog, siteType, style)
            await waitFor200Response()
            const addBlogPageToNav = goals.some((goal) => goal.slug === 'blog')

            navPages = [...pages]

            navPages = addBlogPageToNav
                ? [...navPages, blogPage]
                : [...navPages]

            // Add plugin related pages only if plugin is active
            if (
                activePlugins?.filter((p) => p.includes('woocommerce'))?.length
            ) {
                const shopPageId = await getOption('woocommerce_shop_page_id')
                const shopPage = await getPageById(shopPageId)
                const cartPageId = await getOption('woocommerce_cart_page_id')
                const cartPage = await getPageById(cartPageId)
                if (shopPageId && shopPage && cartPageId && cartPage) {
                    const wooShopPage = {
                        id: shopPageId,
                        slug: shopPage.slug,
                        title: shopPage.title.rendered,
                    }
                    const wooCartPage = {
                        id: cartPageId,
                        slug: cartPage.slug,
                        title: cartPage.title.rendered,
                    }
                    navPages = [...navPages, wooShopPage, wooCartPage]
                }
            }

            if (
                activePlugins?.filter((p) => p.includes('the-events-calendar'))
                    ?.length
            ) {
                const eventsPage = {
                    slug: 'events',
                    title: __('Events', 'extendify'),
                }
                navPages = [...navPages, eventsPage]
            }

            const updatedHeaderCode = addLaunchPagesToNav(
                navPages,
                pageIds,
                style?.headerCode,
            )

            await waitFor200Response()
            await updateTemplatePart('extendable/header', updatedHeaderCode)

            inform(__('Setting up your site assistant', 'extendify'))
            informDesc(__('Helping your site to be successful...', 'extendify'))
            await new Promise((resolve) => setTimeout(resolve, 1000))
            await waitFor200Response()
            await trashWordpressPages([
                { slug: 'hello-world', type: 'post' },
                { slug: 'sample-page', type: 'page' },
            ])
            await waitFor200Response()
            await updateOption('permalink_structure', '/%postname%/')
            inform(__('Your site has been created!', 'extendify'))
            informDesc(__('Redirecting in 3, 2, 1...', 'extendify'))
            // fire confetti here
            setConfettiReady(true)
            setWarnOnLeaveReady(false)
            await new Promise((resolve) => setTimeout(resolve, 2500))

            await waitFor200Response()
            await updateOption(
                'extendify_onboarding_completed',
                new Date().toISOString(),
            )

            return pageIds
        } catch (e) {
            console.error(e)
            // if the error is 4xx, we should stop trying and prompt them to reload
            if (e.status >= 400 && e.status < 500) {
                setWarnOnLeaveReady(false)
                const alertMsg = __(
                    'We encountered a server error we cannot recover from. Please reload the page and try again.',
                    'extendify',
                )
                alert(alertMsg)
                location.href = window.extOnbData.adminUrl
            }
            await new Promise((resolve) => setTimeout(resolve, 2000))
            return doEverything()
        }
    }, [
        activePlugins,
        goals,
        pages,
        plugins,
        siteType,
        style,
        variation,
        canLaunch,
        siteInformation.title,
    ])

    useEffect(() => {
        if (!activePlugins) return
        doEverything().then(() => {
            window.location.replace(
                window.extOnbData.adminUrl +
                    'admin.php?page=extendify-assist&extendify-launch-success',
            )
        })
    }, [doEverything, activePlugins])

    useEffect(() => {
        const documentStyles = window.getComputedStyle(document.documentElement)
        const partnerBg = documentStyles?.getPropertyValue(
            '--ext-partner-theme-primary-bg',
        )
        const partnerText = documentStyles?.getPropertyValue(
            '--ext-partner-theme-primary-text',
        )
        if (partnerBg) {
            setConfettiColors([
                colord(partnerBg).darken(0.3).toHex(),
                colord(partnerText).alpha(0.5).toHex(),
                colord(partnerBg).lighten(0.2).toHex(),
            ])
        }
    }, [])

    useConfetti(
        {
            particleCount: 3,
            angle: 320,
            spread: 220,
            origin: { x: 0, y: 0 },
            colors: confettiColors,
        },
        2500,
        confettiReady,
    )

    return (
        <Transition
            show={isShowing}
            appear={true}
            enter="transition-all ease-in-out duration-500"
            enterFrom="md:w-40vw md:max-w-md"
            enterTo="md:w-full md:max-w-full"
            className="bg-partner-primary-bg text-partner-primary-text py-12 px-10 md:h-screen flex flex-col justify-between md:w-40vw md:max-w-md flex-shrink-0">
            <div className="max-w-prose">
                <div className="md:min-h-48">
                    {window.extOnbData?.partnerLogo && (
                        <div className="pb-8">
                            <img
                                style={{ maxWidth: '200px' }}
                                src={window.extOnbData.partnerLogo}
                                alt={window.extOnbData?.partnerName ?? ''}
                            />
                        </div>
                    )}
                    <div>
                        {info.map((step, index) => {
                            if (!index) {
                                return (
                                    <Transition
                                        appear={true}
                                        show={isShowing}
                                        enter="transition-opacity duration-1000"
                                        enterFrom="opacity-0"
                                        enterTo="opacity-100"
                                        leave="transition-opacity duration-1000"
                                        leaveFrom="opacity-100"
                                        leaveTo="opacity-0"
                                        className="text-4xl flex space-x-4 items-center"
                                        key={step}>
                                        {step}
                                    </Transition>
                                )
                            }
                        })}
                        <div className="flex space-x-4 items-center mt-6">
                            <Spinner className="spin" />
                            {infoDesc.map((step, index) => {
                                if (!index) {
                                    return (
                                        <Transition
                                            appear={true}
                                            show={isShowing}
                                            enter="transition-opacity duration-1000"
                                            enterFrom="opacity-0"
                                            enterTo="opacity-100"
                                            leave="transition-opacity duration-1000"
                                            leaveFrom="opacity-100"
                                            leaveTo="opacity-0"
                                            className="text-lg"
                                            key={step}>
                                            {step}
                                        </Transition>
                                    )
                                }
                            })}
                        </div>
                    </div>
                </div>
            </div>
            <div className="hidden md:flex items-center space-x-3">
                <span className="opacity-70 text-xs">
                    {__('Powered by', 'extendify')}
                </span>
                <span className="relative">
                    <Logo className="logo text-partner-primary-text w-28" />
                    <span className="absolute -bottom-2 right-3 font-semibold tracking-tight">
                        Launch
                    </span>
                </span>
            </div>
        </Transition>
    )
}
