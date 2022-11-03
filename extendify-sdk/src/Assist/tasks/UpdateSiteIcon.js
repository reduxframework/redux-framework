import { useEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { ImageUploader } from '@assist/components/ImageUploader'

export const UpdateSiteIcon = ({ setModalTitle }) => {
    useEffect(() => {
        setModalTitle(__('Upload site icon', 'extendify'))
    }, [setModalTitle])

    return (
        <ImageUploader
            type="site_icon"
            title={__('Site icon', 'extendify')}
            actionLabel={__('Set site icon', 'extendify')}
        />
    )
}
