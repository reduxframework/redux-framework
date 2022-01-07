import { Button } from '@wordpress/components'

export default function PromotionNotice({ promotionData }) {
    return (
        <>
            <span className="text-black">{promotionData?.text ?? ''}</span>
            <span className="px-2 opacity-50" aria-hidden="true">
                &#124;
            </span>
            <div className="flex space-x-2 justify-center items-center">
                {promotionData?.url && (
                    <Button
                        variant="link"
                        className="text-black underline hover:no-underline p-0 h-auto"
                        href={`${promotionData.url}?utm_source=${window.extendifyData.sdk_partner}`}
                        target="_blank">
                        {promotionData?.button_text}
                    </Button>
                )}
            </div>
        </>
    )
}
