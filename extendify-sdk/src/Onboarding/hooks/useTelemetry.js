import { useEffect, useState } from '@wordpress/element'
import { createOrder } from '@onboarding/api/DataApi'
import { useGlobalStore } from '@onboarding/state/Global'
import { usePagesStore } from '@onboarding/state/Pages'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'

export const useTelemetry = () => {
    const {
        goals: selectedGoals,
        pages: selectedPages,
        plugins: selectedPlugins,
        siteType: selectedSiteType,
        style: selectedStyle,
        feedbackMissingSiteType,
        feedbackMissingGoal,
        siteTypeSearch,
    } = useUserSelectionStore()
    const { orderId, setOrderId, generating } = useGlobalStore()
    const { pages, currentPageIndex } = usePagesStore()
    const [url, setUrl] = useState()
    const [stepProgress, setStepProgress] = useState([])
    const [viewedStyles, setViewedStyles] = useState(new Set())

    useEffect(() => {
        const p = [...pages].map((p) => p[0])
        // Add pages as they move around
        setStepProgress((progress) =>
            progress?.at(-1) === p[currentPageIndex]
                ? progress
                : [...progress, p[currentPageIndex]],
        )
    }, [currentPageIndex, pages])

    useEffect(() => {
        if (!generating) return
        // They pressed Launch
        setStepProgress((progress) => [...progress, 'launched'])
    }, [generating])

    useEffect(() => {
        if (!Object.keys(selectedStyle ?? {})?.length) return
        // Add selectedStyle to the set
        setViewedStyles((styles) => {
            const newStyles = new Set(styles)
            newStyles.add(selectedStyle.recordId)
            return newStyles
        })
    }, [selectedStyle])

    useEffect(() => {
        let mode = 'onboarding'
        const search = window.location?.search
        mode = search?.indexOf('DEVMODE') > -1 ? 'onboarding-dev' : mode
        mode = search?.indexOf('LOCALMODE') > -1 ? 'onboarding-local' : mode
        setUrl(window?.extOnbData?.config?.api[mode])
    }, [])

    useEffect(() => {
        if (!url || orderId?.length) return
        // Create a order that persists over local storage
        createOrder().then((response) => {
            setOrderId(response.data.id)
        })
    }, [url, setOrderId, orderId])

    useEffect(() => {
        if (!url || !orderId) return
        let id = 0
        id = window.setTimeout(() => {
            fetch(`${url}/progress`, {
                method: 'POST',
                headers: { 'Content-type': 'application/json' },
                body: JSON.stringify({
                    orderId,
                    selectedGoals: selectedGoals?.map((g) => g.id),
                    selectedPages: selectedPages?.map((p) => p.id),
                    selectedPlugins,
                    selectedSiteType: selectedSiteType?.recordId
                        ? [selectedSiteType.recordId]
                        : [],
                    selectedStyle: selectedStyle?.recordId
                        ? [selectedStyle.recordId]
                        : [],
                    stepProgress,
                    pages,
                    viewedStyles: [...viewedStyles].slice(1),
                    feedbackMissingSiteType,
                    feedbackMissingGoal,
                    siteTypeSearch,
                    perfStyles: getPerformance('style'),
                    perfPages: getPerformance('page'),
                    insightsId: window.extOnbData?.insightsId,
                }),
            })
        }, 1000)
        return () => window.clearTimeout(id)
    }, [
        url,
        selectedGoals,
        selectedPages,
        selectedPlugins,
        selectedSiteType,
        selectedStyle,
        pages,
        orderId,
        stepProgress,
        viewedStyles,
        feedbackMissingSiteType,
        feedbackMissingGoal,
        siteTypeSearch,
    ])
}

const getPerformance = (type) => {
    return performance
        .getEntriesByType('measure')
        .filter((m) => m.detail.extendify && m.detail.context.type === type)
        .map((m) => ({ [m.name]: m.duration }))
}
