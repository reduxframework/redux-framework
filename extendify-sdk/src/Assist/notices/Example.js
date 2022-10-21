import { useGlobalStore } from '../state/Global'

const noticeKey = 'next-steps'
export const NextSteps = () => {
    const { isDismissed, dismissNotice } = useGlobalStore()
    // To avoid content flash, we load in this partial piece of state early via php
    const dismissed = window.extAssistData.dismissedNotices.find(
        (notice) => notice.id === noticeKey,
    )

    if (!dismissed || isDismissed(noticeKey)) return null

    return (
        <div>
            Notice
            <button type="button" onClick={() => dismissNotice(noticeKey)}>
                dismiss
            </button>
        </div>
    )
}
