import { __, sprintf } from '@wordpress/i18n'
import { SWRConfig } from 'swr'
import { PagesList } from '@assist/components/PagesList'
import { TasksList } from '@assist/components/TasksList'

const Page = () => (
    <div>
        <div className="pt-12 flex justify-center flex-col">
            <h2 className="text-center text-3xl">
                {sprintf(__('Welcome to %s', 'extendify'), 'Assist')}
            </h2>
            <p className="text-center text-xl">
                {__(
                    'Manage your site content from a centralized location.',
                    'extendify',
                )}
            </p>
            <TasksList />
            <PagesList />
        </div>
    </div>
)

export const AssistLandingPage = () => (
    <SWRConfig
        value={{
            onErrorRetry: (error, key, config, revalidate, { retryCount }) => {
                if (error.status === 404) return
                if (error?.data?.status === 403) {
                    // if they are logged out, we can't recover
                    window.location.reload()
                    return
                }

                // Retry after 5 seconds.
                setTimeout(() => revalidate({ retryCount }), 5000)
            },
        }}>
        <Page />
    </SWRConfig>
)
