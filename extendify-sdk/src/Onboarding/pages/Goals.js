import { CheckboxControl } from '@wordpress/components'
import { useEffect, useRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { getGoals } from '@onboarding/api/DataApi'
import { useFetch } from '@onboarding/hooks/useFetch'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { usePagesStore } from '@onboarding/state/Pages'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'

export const fetcher = () => getGoals()
export const fetchData = () => ({ key: 'goals' })
export const Goals = () => {
    const { data: goals, loading } = useFetch(fetchData, fetcher)
    const { toggle, has } = useUserSelectionStore()
    const nextPage = usePagesStore((state) => state.nextPage)
    const initialFocus = useRef()

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
                <h1 className="text-3xl text-white mb-4 mt-0">
                    {__(
                        'What do you want to accomplish with this new site?',
                        'extendify',
                    )}
                </h1>
                <p className="text-base opacity-70">
                    {__('You can change these later.', 'extendify')}
                </p>
            </div>
            <div className="w-full">
                <p className="mt-0 mb-8 text-base">
                    {__('Select the goals relevant to your site:', 'extendify')}
                </p>
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
                        {goals &&
                            goals?.map((goal, index) => (
                                <div
                                    key={goal.id}
                                    className="border p-4"
                                    ref={
                                        index === 0 ? initialFocus : undefined
                                    }>
                                    <CheckboxControl
                                        label={goal.title}
                                        checked={has('goals', goal)}
                                        onChange={() => toggle('goals', goal)}
                                    />
                                </div>
                            ))}
                    </form>
                )}
            </div>
        </PageLayout>
    )
}
