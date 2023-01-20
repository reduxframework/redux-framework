import { SWRConfig } from 'swr'
import { useRouter } from '@assist/hooks/useRouter'
import { WelcomeNotice } from '@assist/notices/WelcomeNotice'
import { Header } from '@assist/pages/parts/Header'

const Page = () => {
    const { CurrentPage } = useRouter()
    return (
        <>
            <Header />
            <WelcomeNotice />
            <CurrentPage />
        </>
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
