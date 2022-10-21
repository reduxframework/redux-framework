import { __ } from '@wordpress/i18n'
import { ImageUploader } from '@assist/components/ImageUploader'

export const UpdateSiteIcon = () => (
    <ImageUploader
        type="site_icon"
        title={__('Site icon', 'extendify')}
        actionLabel={__('Set site icon', 'extendify')}
        modalTitle={__('Upload site icon', 'extendify')}
    />
)
