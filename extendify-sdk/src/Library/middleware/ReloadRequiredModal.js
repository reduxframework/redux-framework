import { Modal, Button, ButtonGroup } from '@wordpress/components'
import { dispatch, select } from '@wordpress/data'
import { useState } from '@wordpress/element'
import { __ } from '@wordpress/i18n'

export default function ReloadRequiredModal() {
    const [isSaving, setSaving] = useState(false)
    const { isEditedPostDirty } = select('core/editor')
    const hasUnsavedChanges = isEditedPostDirty()
    const saveChanges = () => {
        setSaving(true)
        dispatch('core/editor').savePost()
        setSaving(false)
    }
    const reload = () => {
        // location.reload()
    }
    if (!hasUnsavedChanges) {
        reload()
        return null
    }
    return (
        <Modal title={__('Reload required', 'extendify')} isDismissible={false}>
            <p
                style={{
                    maxWidth: '400px',
                }}>
                {__(
                    'Just one more thing! We need to reload the page to continue.',
                    'extendify',
                )}
            </p>
            <ButtonGroup>
                <Button isPrimary onClick={reload} disabled={isSaving}>
                    {__('Reload page', 'extendify')}
                </Button>
                <Button
                    isSecondary
                    onClick={saveChanges}
                    isBusy={isSaving}
                    style={{
                        margin: '0 4px',
                    }}>
                    {__('Save changes', 'extendify')}
                </Button>
            </ButtonGroup>
        </Modal>
    )
}
