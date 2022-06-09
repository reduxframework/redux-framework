import { Button } from '@wordpress/components'
import { useUserStore } from '@library/state/User'
import { General } from '../../api/General'

export default function PromotionNotice({ promotionData }) {
    return (
        <>
            <span className="text-black">{promotionData?.text ?? ''}</span>
            <span className="px-2 opacity-50" aria-hidden="true">
                &#124;
            </span>
            <div className="flex items-center justify-center space-x-2">
                {promotionData?.url && (
                    <Button
                        variant="link"
                        className="h-auto p-0 text-black underline hover:no-underline"
                        href={`${promotionData.url}&utm_source=${
                            window.extendifyData.sdk_partner
                        }&utm_group=${useUserStore
                            .getState()
                            .activeTestGroupsUtmValue()}`}
                        onClick={async () =>
                            await General.ping('promotion-notice-click')
                        }
                        target="_blank">
                        {promotionData?.button_text}
                    </Button>
                )}
            </div>
        </>
    )
}
