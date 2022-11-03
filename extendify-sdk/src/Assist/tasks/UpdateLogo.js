import { useEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { ImageUploader } from '@assist/components/ImageUploader'

export const UpdateLogo = ({ setModalTitle }) => {
    useEffect(() => {
        setModalTitle(__('Upload site logo', 'extendify'))
    }, [setModalTitle])

    return (
        <ImageUploader
            type="site_logo"
            title={__('Site logo', 'extendify')}
            actionLabel={__('Set site logo', 'extendify')}
        />
    )
}
