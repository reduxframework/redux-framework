import { useEffect, useState, useCallback } from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'
import { installPlugin, updateTemplatePart } from '@onboarding/api/WPApi'
import { updateOption } from '@onboarding/api/WPApi'
import { useWarnOnLeave } from '@onboarding/hooks/useWarnOnLeave'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import {
    createWordpressPages,
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
        await updateOption('blogname', siteInformation.title)
        await new Promise((resolve) => setTimeout(resolve, 2000))
        inform(__('Installing your theme', 'extendify'))
        await updateGlobalStyleVariant(style.variation)
        if (plugins?.length) {
            inform(__('Installing plugins', 'extendify'))
            await Promise.all([
                ...plugins.map((plugin) => installPlugin(plugin.wordpressSlug)),
                new Promise((resolve) => setTimeout(resolve, 2000)),
            ])
        }
        await new Promise((resolve) => setTimeout(resolve, 2000))
        inform(__('Inserting header area', 'extendify'))
        await updateTemplatePart('extendable//header', style?.headerCode)
        await new Promise((resolve) => setTimeout(resolve, 2000))
        inform(__('Generating page content', 'extendify'))
        const pageIds = await createWordpressPages(pages, siteType, style)
        await new Promise((resolve) => setTimeout(resolve, 2000))
        inform(__('Inserting footer area', 'extendify'))
        await updateTemplatePart('extendable//footer', style?.footerCode)
        await new Promise((resolve) => setTimeout(resolve, 2000))
        inform(__('Finalizing your site', 'extendify'))
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
                <p className="text-base">
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
