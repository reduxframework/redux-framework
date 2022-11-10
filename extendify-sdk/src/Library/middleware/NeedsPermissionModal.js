import { Modal, Button } from '@wordpress/components'
import { render } from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'
import ExtendifyLibrary from '@library/ExtendifyLibrary'
import { useWantedTemplateStore } from '@library/state/Importing'
import { getPluginDescription } from '@library/util/general'

export default function NeedsPermissionModal() {
    const wantedTemplate = useWantedTemplateStore(
        (store) => store.wantedTemplate,
    )
    const closeModal = () =>
        render(
            <ExtendifyLibrary show={true} />,
            document.getElementById('extendify-root'),
        )
    const requiredPlugins = wantedTemplate?.fields?.required_plugins || []
    return (
        <Modal
            title={__('Plugins required', 'extendify')}
            isDismissible={false}>
            <p
                style={{
                    maxWidth: '400px',
                }}>
                {sprintf(
                    // translators: %s is the template type (e.g. "pattern", "layout", etc.)
                    __(
                        'In order to add this %s to your site, the following plugins are required to be installed and activated.',
                        'extendify',
                    ),
                    wantedTemplate?.fields?.type ?? 'template',
                )}
            </p>
            <ul>
                {
                    // Hardcoded temporarily to not force EP install
                    // requiredPlugins.map((plugin) =>
                    requiredPlugins
                        .filter((p) => p !== 'editorplus')
                        .map((plugin) => (
                            <li key={plugin}>{getPluginDescription(plugin)}</li>
                        ))
                }
            </ul>
            <p
                style={{
                    maxWidth: '400px',
                    fontWeight: 'bold',
                }}>
                {__(
                    'Please contact a site admin for assistance in adding these plugins to your site.',
                    'extendify',
                )}
            </p>
            <Button
                isPrimary
                onClick={closeModal}
                style={{
                    boxShadow: 'none',
                }}>
                {__('Return to library', 'extendify')}
            </Button>
        </Modal>
    )
}
