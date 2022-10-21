import { __ } from '@wordpress/i18n'
import { ImageUploader } from '@assist/components/ImageUploader'

export const UpdateLogo = () => (
    <ImageUploader
        type="site_logo"
        title={__('Site logo', 'extendify')}
        actionLabel={__('Set site logo', 'extendify')}
        modalTitle={__('Upload logo', 'extendify')}
    />
)
