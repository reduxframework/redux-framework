import { Modal, Button, Notice } from '@wordpress/components'
import { render } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import RequiredPluginsModal from './RequiredPluginsModal'

export default function ErrorInstalling({ msg }) {
    const goBack = () =>
        render(
            <RequiredPluginsModal />,
            document.getElementById('extendify-root'),
        )

    return (
        <Modal
            style={{
                maxWidth: '500px',
            }}
            title={__('Error installing plugins', 'extendify')}
            isDismissible={false}>
            {__(
                'You have encountered an error that we cannot recover from. Please try again.',
                'extendify',
            )}
            <br />
            <Notice isDismissible={false} status="error">
                {msg}
            </Notice>
            <Button isPrimary onClick={goBack}>
                {__('Go back', 'extendify')}
            </Button>
        </Modal>
    )
}
