import { CheckboxControl } from '@wordpress/components'
import { useCallback, useEffect, useMemo } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { getSuggestedPlugins } from '@onboarding/api/DataApi'
import { Card } from '@onboarding/components/Card'
import { useFetch } from '@onboarding/hooks/useFetch'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { stripUrlParams } from '@onboarding/lib/util'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'

export const fetcher = () => getSuggestedPlugins()
export const fetchData = () => ({ key: 'suggested-plugins' })
export const SuggestedPlugins = () => {
    // Airtable will tag the image url with a timestamp thus forcing a re-render on every fetch
    // This will slow down revalidation to only occur if they are idle for 10 minutes
    const { data: suggestedPlugins } = useFetch(fetchData, fetcher, {
        dedupingInterval: 60_000,
        refreshInterval: 0,
    })
    const { plugins, goals, toggle, has, add, remove } = useUserSelectionStore()

    const nothingToRecommend = useMemo(() => {
        if (!goals?.length) return true
        // If no suggested plugins match any of the goals, return false
        return !goals?.find((goal) => {
            return suggestedPlugins?.some((plugin) =>
                plugin?.goals?.includes(goal?.slug),
            )
        })
    }, [goals, suggestedPlugins])

    const hasGoal = useCallback(
        (plugin) => {
            // True if we have no recommendations
            if (nothingToRecommend) return true
            // Otherwise check the goal/suggestion overlap
            const goalSlugs = goals.map((goal) => goal.slug)
            return plugin?.goals.find((goalSlug) =>
                goalSlugs.includes(goalSlug),
            )
        },
        [goals, nothingToRecommend],
    )

    const allSelected =
        suggestedPlugins?.filter(hasGoal)?.length === plugins?.length

    const toggleAll = () => {
        suggestedPlugins?.filter(hasGoal)?.forEach((plugin) => {
            allSelected ? remove('plugins', plugin) : add('plugins', plugin)
        })
    }

    useEffect(() => {
        // Select all plugins that match goals on mount
        suggestedPlugins
            ?.filter(hasGoal)
            ?.forEach((plugin) => add('plugins', plugin))
    }, [suggestedPlugins, add, nothingToRecommend, hasGoal])

    return (
        <PageLayout>
            <div>
                <h1 className="text-3xl text-white mb-4 mt-0">
                    {__('Choose from these recommended plugins', 'extendify')}
                </h1>
                <p className="text-base opacity-70">
                    {__('You may add more later', 'extendify')}
                </p>
            </div>
            <div className="w-full">
                <div className="flex justify-between">
                    <p className="mt-0 mb-8 text-base">
                        {__('Install recommended plugins:', 'extendify')}
                    </p>
                    <CheckboxControl
                        label={__('Include all plugins', 'extendify')}
                        checked={allSelected}
                        onChange={toggleAll}
                    />
                </div>
                <div className="grid w-full md:grid-cols-3 gap-8">
                    {suggestedPlugins?.filter(hasGoal)?.map((plugin) => (
                        <Card
                            key={plugin.id}
                            image={stripUrlParams(plugin.previewImage)}
                            selected={has('plugins', plugin)}
                            onClick={() => toggle('plugins', plugin)}
                            name={plugin.name}
                            heading={plugin.heading}
                            description={plugin.description}
                        />
                    ))}
                </div>
            </div>
        </PageLayout>
    )
}
