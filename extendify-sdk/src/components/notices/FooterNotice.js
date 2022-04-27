import { Button } from '@wordpress/components'
import { useState, useEffect, useRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { Icon, closeSmall } from '@wordpress/icons'
import { General } from '@extendify/api/General'
import { useGlobalStore } from '@extendify/state/GlobalState'
import { useUserStore } from '@extendify/state/User'
import FeedbackNotice from './FeedbackNotice'
import { InstallStandaloneNotice } from './InstallStandaloneNotice'
import PromotionNotice from './PromotionNotice'

// import WelcomeNotice from './WelcomeNotice'

const NoticesByPriority = {
    // welcome: WelcomeNotice,
    promotion: PromotionNotice,
    feedback: FeedbackNotice,
    standalone: InstallStandaloneNotice,
}

export default function FooterNotice({ className = '' }) {
    const [hasNotice, setHasNotice] = useState(null)
    const once = useRef(false)
    const promotionData = useGlobalStore(
        (state) => state.metaData?.banners?.footer,
    )

    const showFeedback = () => {
        const imports = useUserStore.getState().imports ?? 0
        const firstLoadedOn =
            useUserStore.getState()?.firstLoadedOn ?? new Date()
        const timeDifference =
            new Date().getTime() - new Date(firstLoadedOn).getTime()
        const daysSinceActivated = timeDifference / 86_400_000 // 24 hours

        return imports >= 3 && daysSinceActivated > 3
    }

    // Find the first notice key to use
    // TODO: extract this logic into the individual component instead of controlling it here
    const componentKey =
        Object.keys(NoticesByPriority).find((key) => {
            if (key === 'promotion') {
                return (
                    // When checking promotions, use the key sent from the server
                    // to check whether it's been dismissed
                    !useUserStore.getState().apiKey?.length &&
                    promotionData?.key &&
                    !useUserStore.getState().noticesDismissedAt[
                        promotionData.key
                    ]
                )
            }

            if (key === 'feedback') {
                return (
                    showFeedback() &&
                    !useUserStore.getState().noticesDismissedAt[key]
                )
            }

            if (key === 'standalone') {
                return (
                    !window.extendifyData.standalone &&
                    !useUserStore.getState().noticesDismissedAt[key]
                )
            }

            return !useUserStore.getState().noticesDismissedAt[key]
        }) ?? null
    const Notice = NoticesByPriority[componentKey]

    const dismiss = async () => {
        setHasNotice(false)
        // The noticesDismissedAt key will either be the key from NoticesByPriority,
        // or a key passed in from the server, such as 'holiday-sale2077'
        const key =
            componentKey === 'promotion' ? promotionData.key : componentKey
        useUserStore.getState().markNoticeSeen(key, 'notices')
        await General.ping(`footer-notice-x-${key}`)
    }

    useEffect(() => {
        // Only show the notice once on main render and only if a notice exists.
        if (NoticesByPriority[componentKey] && !once.current) {
            setHasNotice(true)
            once.current = true
        }
    }, [componentKey])

    if (!hasNotice || !Notice) {
        return null
    }
    return (
        <div
            className={`${className} relative mx-auto hidden max-w-screen-4xl items-center justify-center space-x-4 bg-extendify-secondary py-3 px-5 lg:flex`}>
            {/* Pass all data to all components and let them decide what they use */}
            <Notice promotionData={promotionData} />
            <div className="absolute right-1">
                <Button
                    className="text-extendify-black opacity-50 hover:opacity-100 focus:opacity-100"
                    icon={<Icon icon={closeSmall} />}
                    label={__('Dismiss this notice', 'extendify')}
                    onClick={dismiss}
                    showTooltip={false}
                />
            </div>
        </div>
    )
}
