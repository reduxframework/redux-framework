import { GuidedTour } from '@assist/components/GuidedTour'
import { Modal } from '@assist/components/Modal'
import { useDesignColors } from '@assist/hooks/useDesignColors'

export const Assist = () => {
    useDesignColors()
    return (
        <>
            <Modal />
            <GuidedTour />
        </>
    )
}
