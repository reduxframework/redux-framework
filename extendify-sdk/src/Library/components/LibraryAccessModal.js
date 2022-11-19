import { Modal } from '@wordpress/components'
import { ToggleControl } from '@wordpress/components'
import { useSelect } from '@wordpress/data'
import { unmountComponentAtNode, useState, useEffect } from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'
import { useSiteSettingsStore } from '@library/state/SiteSettings'
import { useUserStore } from '@library/state/User'

const LibraryAccessModal = () => {
    const isAdmin = useSelect((select) =>
        select('core').canUser('create', 'users'),
    )

    const [libraryforMyself, setLibraryforMyself] = useState(
        useUserStore.getState().enabled,
    )
    const [libraryforEveryone, setLibraryforEveryone] = useState(
        useSiteSettingsStore.getState().enabled,
    )

    const closeModal = () => {
        const util = document.getElementById('extendify-util')
        unmountComponentAtNode(util)
    }

    useEffect(() => {
        hideButton(!libraryforMyself)
    }, [libraryforMyself])

    function hideButton(state = true) {
        const button = document.getElementById(
            'extendify-templates-inserter-btn',
        )
        if (!button) return
        if (state) {
            button.classList.add('hidden')
        } else {
            button.classList.remove('hidden')
        }
    }

    async function saveUser(value) {
        await useUserStore.setState({ enabled: value })
    }

    async function saveSetting(value) {
        await useSiteSettingsStore.setState({ enabled: value })
    }

    async function saveToggle(state, type) {
        if (type === 'global') {
            await saveSetting(state)
        } else {
            await saveUser(state)
        }
    }

    function handleToggle(type) {
        if (type === 'global') {
            setLibraryforEveryone((state) => {
                saveToggle(!state, type)
                return !state
            })
        } else {
            setLibraryforMyself((state) => {
                hideButton(!state)
                saveToggle(!state, type)
                return !state
            })
        }
    }

    return (
        <Modal
            title={sprintf(
                // translators: %s: The name of the plugin, Extendify.
                __('%s Settings', 'extendify'),
                'Extendify',
            )}
            onRequestClose={closeModal}>
            <ToggleControl
                label={
                    isAdmin
                        ? __('Enable the library for myself', 'extendify')
                        : __('Enable the library', 'extendify')
                }
                help={__(
                    'Publish with hundreds of patterns & page layouts',
                    'extendify',
                )}
                checked={libraryforMyself}
                onChange={() => handleToggle('user')}
            />

            {isAdmin && (
                <>
                    <br />
                    <ToggleControl
                        label={__(
                            'Allow all users to publish with the library',
                            'extendify',
                        )}
                        help={__(
                            'Everyone publishes with patterns & page layouts',
                            'extendify',
                        )}
                        checked={libraryforEveryone}
                        onChange={() => handleToggle('global')}
                    />
                </>
            )}
        </Modal>
    )
}

export default LibraryAccessModal
