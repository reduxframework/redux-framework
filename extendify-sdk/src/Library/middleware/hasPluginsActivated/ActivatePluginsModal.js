import { Modal, Button, ButtonGroup } from '@wordpress/components'
import { render } from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'
import ExtendifyLibrary from '@library/ExtendifyLibrary'
import { useWantedTemplateStore } from '@library/state/Importing'
import { useUserStore } from '@library/state/User'
import { getPluginDescription } from '@library/util/general'
import NeedsPermissionModal from '../NeedsPermissionModal'
import ActivatingModal from './ActivatingModal'

export default function ActivatePluginsModal(props) {
    const wantedTemplate = useWantedTemplateStore(
        (store) => store.wantedTemplate,
    )
    const closeModal = () =>
        render(
            <ExtendifyLibrary show={true} />,
            document.getElementById('extendify-root'),
        )
    const installPlugins = () =>
        render(<ActivatingModal />, document.getElementById('extendify-root'))
    const requiredPlugins = wantedTemplate?.fields?.required_plugins || []

    if (!useUserStore.getState()?.canActivatePlugins) {
        return <NeedsPermissionModal />
    }

    return (
        <Modal
            title={__('Activate required plugins', 'extendify')}
            isDismissible={false}>
            <div>
                <p
                    style={{
                        maxWidth: '400px',
                    }}>
                    {props.message ??
                        __(
                            sprintf(
                                'There is just one more step. This %s requires the following plugins to be installed and activated:',
                                wantedTemplate?.fields?.type ?? 'template',
                            ),
                            'extendify',
                        )}
                </p>
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
                <ButtonGroup>
                    <Button isPrimary onClick={installPlugins}>
                        {__('Activate Plugins', 'extendify')}
                    </Button>
                    {props.showClose && (
                        <Button
                            isTertiary
                            onClick={closeModal}
                            style={{
                                boxShadow: 'none',
                                margin: '0 4px',
                            }}>
                            {__('No thanks, return to library', 'extendify')}
                        </Button>
                    )}
                </ButtonGroup>
            </div>
        </Modal>
    )
}
