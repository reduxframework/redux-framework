import { __ } from '@wordpress/i18n'
import { Modal, Button } from '@wordpress/components'
import { useState, render } from '@wordpress/element'
import { Plugins } from '../../api/Plugins'
import { useWantedTemplateStore } from '../../state/Importing'
import ErrorActivating from './ErrorActivating'
import ReloadRequiredModal from '../ReloadRequiredModal'

export default function ActivatingModal() {
    const [errorMessage, setErrorMessage] = useState('')
    const wantedTemplate = useWantedTemplateStore(
        (store) => store.wantedTemplate,
    )

    // Hardcoded temporarily to not force EP install
    // const required = wantedTemplate?.fields?.required_plugins
    const required = wantedTemplate?.fields?.required_plugins.filter(
        (p) => p !== 'editorplus',
    )

    Plugins.installAndActivate(required)
        .then(() => {
            useWantedTemplateStore.setState({
                importOnLoad: true,
            })
        })
        .then(async () => {
            await new Promise((resolve) => setTimeout(resolve, 1000))
            render(
                <ReloadRequiredModal />,
                document.getElementById('extendify-root'),
            )
        })
        .catch(({ response }) => {
            setErrorMessage(response.data.message)
        })

    if (errorMessage) {
        return <ErrorActivating msg={errorMessage} />
    }

    return (
        <Modal
            title={__('Activating plugins', 'extendify-sdk')}
            isDismissible={false}>
            <Button
                style={{
                    width: '100%',
                }}
                disabled
                isPrimary
                isBusy
                onClick={() => {}}>
                {__('Activating...', 'extendify-sdk')}
            </Button>
        </Modal>
    )
}
