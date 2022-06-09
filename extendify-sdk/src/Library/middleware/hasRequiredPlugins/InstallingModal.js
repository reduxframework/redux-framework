import { Modal, Button } from '@wordpress/components'
import { useState, render } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { Plugins } from '@library/api/Plugins'
import { useWantedTemplateStore } from '@library/state/Importing'
import ReloadRequiredModal from '../ReloadRequiredModal'
import ErrorInstalling from './ErrorInstalling'

export default function InstallingModal({ requiredPlugins }) {
    const [errorMessage, setErrorMessage] = useState('')
    const wantedTemplate = useWantedTemplateStore(
        (store) => store.wantedTemplate,
    )

    // Hardcoded temporarily to not force EP install
    // const required = wantedTemplate?.fields?.required_plugins
    const required =
        requiredPlugins ??
        wantedTemplate?.fields?.required_plugins.filter(
            (p) => p !== 'editorplus',
        )

    Plugins.installAndActivate(required)
        .then(() => {
            useWantedTemplateStore.setState({
                importOnLoad: true,
            })
            render(
                <ReloadRequiredModal />,
                document.getElementById('extendify-root'),
            )
        })
        .catch(({ message }) => {
            setErrorMessage(message)
        })

    if (errorMessage) {
        return <ErrorInstalling msg={errorMessage} />
    }

    return (
        <Modal
            title={__('Installing plugins', 'extendify')}
            isDismissible={false}>
            <Button
                style={{
                    width: '100%',
                }}
                disabled
                isPrimary
                isBusy
                onClick={() => {}}>
                {__('Installing...', 'extendify')}
            </Button>
        </Modal>
    )
}
