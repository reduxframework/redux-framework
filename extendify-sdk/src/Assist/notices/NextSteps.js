import { useGlobalStore, useGlobalStoreReady } from '../state/Global'

const noticeKey = 'next-steps'
export const NextSteps = () => {
    const { isDismissed, dismissNotice } = useGlobalStore()
    const ready = useGlobalStoreReady()

    if (!ready || isDismissed(noticeKey)) return null

    return (
        <div>
            Notice
            <button type="button" onClick={() => dismissNotice(noticeKey)}>
                dismiss
            </button>
        </div>
    )
}
