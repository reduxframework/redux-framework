import { ExternalLink } from '@wordpress/components'
import { __, sprintf } from '@wordpress/i18n'
import { SWRConfig } from 'swr'
import { PagesList } from '@assist/components/PagesList'
import { TasksList } from '@assist/components/TasksList'
import { useAdminColors } from '@assist/hooks/useAdminColors'
import { WelcomeNotice } from '@assist/notices/WelcomeNotice'

const Page = () => {
    const { mainColor } = useAdminColors()
    return (
        <div>
            <WelcomeNotice />
            <div className="max-w-screen-lg mx-auto pt-12 flex justify-center flex-col">
                <h2 className="text-center text-3xl m-0 mb-2">
                    {sprintf(__('Welcome to %s', 'extendify'), 'Assist')}
                </h2>
                <p className="text-center text-xl m-0 p-0">
                    {__(
                        'Manage your site content from a centralized location.',
                        'extendify',
                    )}
                </p>
                <div className="flex justify-center my-8">
                    <ExternalLink
                        style={{ backgroundColor: mainColor }}
                        className="flex items-center gap-1 text-base cursor-pointer rounded px-6 py-2 text-white border-none no-underline"
                        href={window.extAssistData.home}>
                        {__('View site', 'extendify')}
                    </ExternalLink>
                </div>
                <TasksList />
                <PagesList />
            </div>
        </div>
    )
}

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
