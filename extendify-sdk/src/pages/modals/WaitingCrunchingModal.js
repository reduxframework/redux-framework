import { Button, Modal } from '@wordpress/components'
import { useEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'

export default function WaitingCrunchingModal({ action, callback, text }) {
    useEffect(() => {
        action.then(async () => await callback())
    })

    // Currently this is just a basic WP modal that is invoked/rendered outside of the
    // application, but could instead act as a mediary page that renderes within. It's
    // just not yet used there at this time.
    return <Modal
        title={text}
        isDismissible={false}>
        <Button style={{
            width: '100%',
        }} disabled isPrimary isBusy onClick={() => {}}>
            {__('Please wait...', 'extendify-sdk')}
        </Button>
    </Modal>
}
