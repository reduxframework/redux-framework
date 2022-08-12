import { useCallback, useEffect, useMemo } from '@wordpress/element'
import { getSuggestedPlugins } from '@onboarding/api/DataApi'
import { CheckboxInput } from '@onboarding/components/CheckboxInput'
import { useFetch } from '@onboarding/hooks/useFetch'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'

export const fetcher = () => getSuggestedPlugins()
export const fetchData = () => ({ key: 'plugins' })
export const SuggestedPlugins = () => {
    const { data: suggestedPlugins } = useFetch(fetchData, fetcher)
    const { goals, add, toggle, remove } = useUserSelectionStore()

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

    useEffect(() => {
        // Clean up first in case they updated their choices
        suggestedPlugins?.forEach((plugin) => remove('plugins', plugin))

        // If nothing to recommend, don't autoselect anything
        if (nothingToRecommend) return

        // Select all plugins that match goals on mount
        suggestedPlugins
            ?.filter(hasGoal)
            ?.forEach((plugin) => add('plugins', plugin))
    }, [suggestedPlugins, add, nothingToRecommend, hasGoal, remove])

    return (
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
            {suggestedPlugins?.filter(hasGoal)?.map((plugin) => (
                <div key={plugin.id}>
                    <CheckboxInput
                        label={plugin.name}
                        slug={plugin.wordpressSlug}
                        description={plugin.description}
                        checked={!nothingToRecommend}
                        onChange={() => toggle('plugins', plugin)}
                    />
                </div>
            ))}
        </div>
    )
}
