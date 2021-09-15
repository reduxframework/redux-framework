import { __ } from '@wordpress/i18n'
import {
    Modal, Button, Notice,
} from '@wordpress/components'
import { render } from '@wordpress/element'
import ActivatePluginsModal from './ActivatePluginsModal'

export default function ErrorActivating({ msg }) {
    const goBack = () => {
        render(<ActivatePluginsModal />, document.getElementById('extendify-root'))
    }

    return <Modal
        style={{
            maxWidth: '500px',
        }}
        title={__('Error Activating plugins', 'extendify-sdk')}
        isDismissible={false}
    >
        {__('You have encountered an error that we cannot recover from. Please try again.', 'extendify-sdk')}
        <br />
        <Notice isDismissible={false} status="error">
            {msg}
        </Notice>
        <Button isPrimary onClick={goBack}>
            {__('Go back', 'extendify-sdk')}
        </Button>
    </Modal>
}
