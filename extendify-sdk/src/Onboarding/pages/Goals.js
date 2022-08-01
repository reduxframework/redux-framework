import { useEffect, useRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { getGoals } from '@onboarding/api/DataApi'
import { CheckboxInput } from '@onboarding/components/CheckboxInput'
import { useFetch } from '@onboarding/hooks/useFetch'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { usePagesStore } from '@onboarding/state/Pages'
import { useProgressStore } from '@onboarding/state/Progress'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'

export const fetcher = () => getGoals()
export const fetchData = () => ({ key: 'goals' })
export const metadata = {
    key: 'goals',
    title: __('Goals', 'extendify'),
    completed: () => true,
}
export const Goals = () => {
    const { data: goals, loading } = useFetch(fetchData, fetcher)
    const { toggle, has } = useUserSelectionStore()
    const { feedbackMissingGoal: feedback, setFeedbackMissingGoal } =
        useUserSelectionStore()
    const nextPage = usePagesStore((state) => state.nextPage)
    const initialFocus = useRef()
    const touch = useProgressStore((state) => state.touch)

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
                        className="w-full max-w-2xl grid lg:grid-cols-2 gap-4 goal-select">
                        {/* Added so forms can be submitted by pressing Enter */}
                        <input type="submit" className="hidden" />
                        {/* Seems excessive but this keeps failing and crashing randomly */}
                        {goals && goals?.length > 0
                            ? goals?.map((goal, index) => (
                                  <div
                                      key={goal.id}
                                      className="border border-gray-800 rounded-lg p-4"
                                      ref={
                                          index === 0 ? initialFocus : undefined
                                      }>
                                      <CheckboxInput
                                          label={goal.title}
                                          checked={has(metadata.key, goal)}
                                          onChange={() => {
                                              toggle(metadata.key, goal)
                                              touch(metadata.key)
                                          }}
                                      />
                                  </div>
                              ))
                            : null}
                    </form>
                )}
                {!loading && (
                    <div className="w-80">
                        <h2 className="text-lg mt-12 mb-4 text-gray-900">
                            {__(
                                "Don't see what you're looking for?",
                                'extendify',
                            )}
                        </h2>
                        <div className="search-panel flex items-center justify-center relative mb-8">
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
