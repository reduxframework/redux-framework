import { useEffect, useState, useCallback } from '@wordpress/element'
import { __, sprintf, _n } from '@wordpress/i18n'
import { installPlugin, updateTemplatePart } from '@onboarding/api/WPApi'
import { updateOption } from '@onboarding/api/WPApi'
import { useWarnOnLeave } from '@onboarding/hooks/useWarnOnLeave'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import {
    createWordpressPages,
    trashWordpressPages,
    updateGlobalStyleVariant,
} from '@onboarding/lib/wp'
import { useGlobalStore } from '@onboarding/state/Global'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { Checkmark, SpinnerIcon } from '@onboarding/svg'

export const CreatingSite = () => {
    const canLaunch = useUserSelectionStore((state) => state.canLaunch())
    const { siteType, siteInformation, pages, style, plugins } =
        useUserSelectionStore()
    const [info, setInfo] = useState([])
    const inform = (msg) => setInfo((info) => [msg, ...info])
    useWarnOnLeave()

    const doEverything = useCallback(async () => {
        if (!canLaunch) {
            throw new Error(__('Site is not ready to launch.', 'extendify'))
        }
        inform(__('Preparing your website', 'extendify'))
        try {
            await updateOption('blogname', siteInformation.title)
        } catch (e) {
            /* do nothing */
        }
        await new Promise((resolve) => setTimeout(resolve, 2000))
        inform(__('Installing your theme', 'extendify'))
        try {
            await updateGlobalStyleVariant(style?.variation ?? {})
        } catch (e) {
            /* do nothing */
        }
        if (plugins?.length) {
            inform(
                _n(
                    `Getting ready to install ${plugins.length} plugin`,
                    `Getting ready to install ${plugins.length} plugins`,
                    plugins.length,
                    'extendify',
                ),
            )
            await new Promise((resolve) => setTimeout(resolve, 2000))
            for (const [index, plugin] of plugins.entries()) {
                // TODO: instead of updating here, we could have a progress component
                // that we can update a % of the width every index/n
                inform(
                    __(
                        `Installing (${index + 1}/${plugins.length}): ${
                            plugin.name
                        }`,
                        'extendify',
                    ),
                )
                try {
                    await installPlugin(plugin)
                } catch (e) {
                    /* do nothing */
                }
            }
        }
        await new Promise((resolve) => setTimeout(resolve, 2000))
        inform(__('Inserting header area', 'extendify'))
        try {
            await updateTemplatePart('extendable//header', style?.headerCode)
        } catch (e) {
            /* do nothing */
        }
        await new Promise((resolve) => setTimeout(resolve, 2000))
        inform(__('Generating page content', 'extendify'))
        let pageIds
        try {
            pageIds = await createWordpressPages(pages, siteType, style)
        } catch (e) {
            /* do nothing */
        }
        await new Promise((resolve) => setTimeout(resolve, 2000))
        inform(__('Inserting footer area', 'extendify'))
        try {
            await updateTemplatePart('extendable//footer', style?.footerCode)
        } catch (e) {
            /* do nothing */
        }
        await new Promise((resolve) => setTimeout(resolve, 2000))
        inform(__('Finalizing your site', 'extendify'))
        try {
            await trashWordpressPages([
                { slug: 'hello-world', type: 'post' },
                { slug: 'sample-page', type: 'page' },
            ])
        } catch (e) {
            /* do nothing */
        }

        return pageIds
    }, [pages, plugins, siteType, style, canLaunch, siteInformation.title])

    useEffect(() => {
        doEverything().then((pageIds) => {
            // This will load up the finished page
            useGlobalStore.setState({ generatedPages: pageIds })
        })
    }, [doEverything])

    return (
        <PageLayout includeNav={false}>
            <div>
                <h1 className="text-3xl text-partner-primary-text mb-4 mt-0">
                    {__('Building your site now!', 'extendify')}
                </h1>
                <p className="text-base mb-0">
                    {__("Please don't close the window.", 'extendify')}
                </p>
            </div>
            <div className="w-full">
                <div className="flex flex-col items-start space-y-4">
                    {info.map((step, index) => {
                        if (!index) {
                            return (
                                <div
                                    className="text-4xl flex space-x-4 items-center"
                                    key={step}>
                                    <SpinnerIcon className="spin w-10 mr-2" />
                                    {sprintf(step, '...')}
                                </div>
                            )
                        }
                        return (
                            <div
                                className="ml-12 text-base text-gray-500 flex"
                                key={step}>
                                <Checkmark className="text-green-500 w-6 mr-1" />
                                {sprintf(step, '...')}
                            </div>
                        )
                    })}
                </div>
            </div>
        </PageLayout>
    )
}
