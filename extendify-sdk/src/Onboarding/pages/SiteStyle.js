import { useCallback, useEffect, useState } from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'
import { getStyles } from '@onboarding/api/DataApi'
import { StylePreview } from '@onboarding/components/StyledPreview'
import { useFetch } from '@onboarding/hooks/useFetch'
import { useIsMountedLayout } from '@onboarding/hooks/useIsMounted'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { findTheCode } from '@onboarding/lib/util'
import { usePagesStore } from '@onboarding/state/Pages'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { SpinnerIcon } from '@onboarding/svg'

export const fetcher = async (params) => {
    const res = await getStyles(params)
    // TODO: these transforms should be moved to the server eventually
    return res?.data
        ?.map((style) => {
            return {
                label: style.title,
                slug: style.slug,
                recordId: style.id,
                themeJson: style?.themeJson,
                homeBaseLayout: style?.homeBaseLayout,
                header: style?.header,
                footer: style?.footer,
                kit: style?.kit,
                headerCode: style?.headerCode,
                footerCode: style?.footerCode,
                code: findTheCode(style),
            }
        })
        ?.filter((style) => style.code)
}
export const fetchData = (siteType) => {
    siteType = siteType ?? useUserSelectionStore?.getState().siteType
    return {
        key: 'site-style',
        siteType: siteType?.slug ?? 'default',
    }
}
export const SiteStyle = () => {
    const siteType = useUserSelectionStore((state) => state.siteType)
    const nextPage = usePagesStore((state) => state.nextPage)
    const { data, loading } = useFetch(fetchData, fetcher)
    const isMounted = useIsMountedLayout()
    const selectStyle = useCallback(
        (style) => {
            useUserSelectionStore.getState().setStyle(style)
            nextPage()
        },
        [nextPage],
    )
    const [styles, setStyles] = useState([])

    useEffect(() => {
        if (!data?.length) return
        ;(async () => {
            for (const style of data) {
                if (!isMounted.current) return
                setStyles((styles) => [...styles, style])
                await new Promise((resolve) => setTimeout(resolve, 1000))
            }
        })()
    }, [data, isMounted])

    useEffect(() => {
        if (styles?.length && !useUserSelectionStore.getState().style) {
            useUserSelectionStore.getState().setStyle(styles[0])
        }
    }, [styles])

    return (
        <PageLayout>
            <div>
                <h1 className="text-3xl text-white mb-4 mt-0">
                    {sprintf(
                        __(
                            'Now pick a design for your new %s site.',
                            'extendify',
                        ),
                        siteType?.label?.toLowerCase(),
                    )}
                </h1>
                <p className="text-base opacity-70">
                    {__('You can personalize this later.', 'extendify')}
                </p>
            </div>
            <div className="w-full">
                <p className="mt-0 mb-8 text-base">
                    {loading
                        ? __(
                              'Please wait a moment while we generate style previews...',
                              'extendify',
                          )
                        : __('Pick your style', 'extendify')}
                </p>
                <div className="lg:flex space-y-6 -m-6 lg:space-y-0 flex-wrap">
                    {styles?.map((style) => (
                        <div
                            className="p-6 relative"
                            style={{ height: 590, width: 425 }}
                            key={style.recordId}>
                            <StylePreview
                                style={style}
                                selectStyle={selectStyle}
                                blockHeight={590}
                            />
                        </div>
                    ))}
                    {data?.slice(styles?.length).map((data) => (
                        <div
                            key={data.slug}
                            style={{ height: 590, width: 425 }}
                            className="p-6 relative">
                            <div className="bg-gray-50 h-full w-full flex items-center justify-center">
                                <SpinnerIcon className="spin w-8" />
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </PageLayout>
    )
}
