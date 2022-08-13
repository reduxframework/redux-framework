import { useEffect, useRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { getGoals } from '@onboarding/api/DataApi'
import { CheckboxInput } from '@onboarding/components/CheckboxInput'
import { useFetch } from '@onboarding/hooks/useFetch'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { usePagesStore } from '@onboarding/state/Pages'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { pageState } from '@onboarding/state/factory'

export const fetcher = async () => {
    const goals = await getGoals()
    if (!Array.isArray(goals?.data)) {
        throw new Error('Goals data is not an array', goals)
    }
    return goals
}
export const fetchData = () => ({ key: 'goals' })
export const state = pageState('Goals', () => ({
    title: __('Goals', 'extendify'),
    default: undefined,
    showInSidebar: true,
    ready: false,
    isDefault: () => {
        // If no goals are selected and no text is entered
        const { feedbackMissingGoal, goals } = useUserSelectionStore.getState()
        return !feedbackMissingGoal?.length && goals?.length === 0
    },
}))
export const Goals = () => {
    const { data: goals, loading } = useFetch(fetchData, fetcher)
    const { toggle, has } = useUserSelectionStore()
    const { feedbackMissingGoal: feedback, setFeedbackMissingGoal } =
        useUserSelectionStore()
    const nextPage = usePagesStore((state) => state.nextPage)
    const initialFocus = useRef()

    useEffect(() => {
        state.setState({ ready: !loading })
    }, [loading])

    useEffect(() => {
        if (!initialFocus.current) return
        const raf = requestAnimationFrame(() =>
            initialFocus.current.querySelector('input').focus(),
        )
        return () => cancelAnimationFrame(raf)
    }, [initialFocus])

    return (
        <PageLayout>
            <div>
                <h1 className="text-3xl text-partner-primary-text mb-4 mt-0">
                    {__(
                        'What do you want to accomplish with this new site?',
                        'extendify',
                    )}
                </h1>
                <p className="text-base opacity-70 mb-0">
                    {__('You can change these later.', 'extendify')}
                </p>
            </div>
            <div className="w-full">
                <h2 className="text-lg m-0 mb-4 text-gray-900">
                    {__('Select the goals relevant to your site:', 'extendify')}
                </h2>
                {loading ? (
                    <p>{__('Loading...', 'extendify')}</p>
                ) : (
                    <form
                        onSubmit={(e) => {
                            e.preventDefault()
                            nextPage()
                        }}
                        className="w-full grid lg:grid-cols-2 gap-3 goal-select">
                        {/* Added so forms can be submitted by pressing Enter */}
                        <input type="submit" className="hidden" />
                        {goals?.map((goal, index) => (
                            <div
                                key={goal.id}
                                className="border border-gray-800 rounded-lg p-4"
                                ref={index === 0 ? initialFocus : undefined}>
                                <CheckboxInput
                                    label={goal.title}
                                    checked={has('goals', goal)}
                                    onChange={() => {
                                        toggle('goals', goal)
                                    }}
                                />
                            </div>
                        ))}
                    </form>
                )}
                {!loading && (
                    <div className="max-w-onboarding-sm mx-auto">
                        <h2 className="text-lg mt-12 mb-4 text-gray-900">
                            {__(
                                "Don't see what you're looking for?",
                                'extendify',
                            )}
                        </h2>
                        <div className="search-panel flex items-center justify-center relative">
                            <input
                                type="text"
                                className="w-full bg-gray-100 h-12 pl-4 input-focus rounded-none ring-offset-0 focus:bg-white"
                                value={feedback}
                                onChange={(e) =>
                                    setFeedbackMissingGoal(e.target.value)
                                }
                                placeholder={__(
                                    'Add your goals...',
                                    'extendify',
                                )}
                            />
                        </div>
                    </div>
                )}
            </div>
        </PageLayout>
    )
}
