import { __, sprintf } from '@wordpress/i18n'
import ExtendifyLibrary from '../../ExtendifyLibrary'
import { Modal, Button, ButtonGroup } from '@wordpress/components'
import { render } from '@wordpress/element'
import InstallingModal from './InstallingModal'
import { useWantedTemplateStore } from '../../state/Importing'
import { getPluginDescription } from '../../util/general'
import { useUserStore } from '../../state/User'
import NeedsPermissionModal from '../NeedsPermissionModal'

export default function RequiredPluginsModal({
    forceOpen,
    buttonLabel,
    title,
    message,
    requiredPlugins,
}) {
    // If there's a template in cache ready to be installed.
    // TODO: this could probably be refactored out when overhauling required plugins
    const wantedTemplate = useWantedTemplateStore(
        (store) => store.wantedTemplate,
    )
    requiredPlugins =
        requiredPlugins ?? wantedTemplate?.fields?.required_plugins

    const closeModal = () => {
        if (forceOpen) {
            return
        }
        render(
            <ExtendifyLibrary show={true} />,
            document.getElementById('extendify-root'),
        )
    }
    const installPlugins = () =>
        render(
            <InstallingModal requiredPlugins={requiredPlugins} />,
            document.getElementById('extendify-root'),
        )

    if (!useUserStore.getState()?.canInstallPlugins) {
        return <NeedsPermissionModal />
    }

    return (
        <Modal
            title={title ?? __('Install required plugins', 'extendify')}
            isDismissible={false}>
            <p
                style={{
                    maxWidth: '400px',
                }}>
                {message ??
                    __(
                        sprintf(
                            'There is just one more step. This %s requires the following to be automatically installed and activated:',
                            wantedTemplate?.fields?.type ?? 'template',
                        ),
                        'extendify',
                    )}
            </p>
            {message?.length > 0 || (
                <ul>
                    {
                        // Hardcoded temporarily to not force EP install
                        // requiredPlugins.map((plugin) =>
                        requiredPlugins
                            .filter((p) => p !== 'editorplus')
                            .map((plugin) => (
                                <li key={plugin}>
                                    {getPluginDescription(plugin)}
                                </li>
                            ))
                    }
                </ul>
            )}
            <ButtonGroup>
                <Button isPrimary onClick={installPlugins}>
                    {buttonLabel ?? __('Install Plugins', 'extendify')}
                </Button>
                {forceOpen || (
                    <Button
                        isTertiary
                        onClick={closeModal}
                        style={{
                            boxShadow: 'none',
                            margin: '0 4px',
                        }}>
                        {__('No thanks, take me back', 'extendify')}
                    </Button>
                )}
            </ButtonGroup>
        </Modal>
    )
}
