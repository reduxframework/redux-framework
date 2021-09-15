import { __, sprintf } from '@wordpress/i18n'
import ExtendifyLibrary from '../../ExtendifyLibrary'
import {
    Modal, Button, ButtonGroup,
} from '@wordpress/components'
import { render } from '@wordpress/element'
import InstallingModal from './InstallingModal'
import { useWantedTemplateStore } from '../../state/Importing'
import { getPluginDescription } from '../../util/general'
import { useUserStore } from '../../state/User'
import NeedsPermissionModal from '../NeedsPermissionModal'

export default function RequiredPluginsModal(props) {
    const wantedTemplate = useWantedTemplateStore(store => store.wantedTemplate)
    const closeModal = () => {
        if (props.forceOpen) {
            return
        }
        render(<ExtendifyLibrary show={true} />, document.getElementById('extendify-root'))
    }
    const installPlugins = () => render(<InstallingModal />, document.getElementById('extendify-root'))
    const requiredPlugins = wantedTemplate?.fields?.required_plugins || []

    if (!useUserStore.getState()?.canInstallPlugins) {
        return <NeedsPermissionModal/>
    }

    return <Modal
        title={props.title ?? __('Install required plugins', 'extendify-sdk')}
        isDismissible={false}
    >
        <p style={{
            maxWidth: '400px',
        }}>
            {props.message ?? __(sprintf('There is just one more step. This %s requires the following to be automatically installed and activated:',
                wantedTemplate?.fields?.type ?? 'template'),
            'extendify-sdk')}
        </p>
        {props.message?.length > 0 || <ul>
            {
                // Hardcoded temporarily to not force EP install
                // requiredPlugins.map((plugin) =>
                requiredPlugins.filter((p) => p !== 'editorplus').map((plugin) =>
                    <li key={plugin}>
                        {getPluginDescription(plugin)}
                    </li>)
            }
        </ul>}
        <ButtonGroup>
            <Button isPrimary onClick={installPlugins}>
                {props.buttonLabel ?? __('Install Plugins', 'extendify-sdk')}
            </Button>
            {props.forceOpen || <Button isTertiary onClick={closeModal} style={{
                boxShadow: 'none', margin: '0 4px',
            }}>
                {__('No thanks, take me back', 'extendify-sdk')}
            </Button>}
        </ButtonGroup>
    </Modal>
}
