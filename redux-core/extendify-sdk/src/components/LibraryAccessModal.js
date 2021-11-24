import { Modal } from '@wordpress/components'
import { unmountComponentAtNode, useState, useEffect } from '@wordpress/element'
import { ToggleControl } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import { useSelect } from '@wordpress/data'

import { useUserStore } from '../state/User'
import { useSiteSettingsStore } from '../state/SiteSettings'

const LibraryAccessModal = () => {
    const isAdmin = useSelect((select) =>
        select('core').canUser('create', 'users'),
    )

    const [libraryforMyself, setLibraryforMyself] = useState(
        useUserStore((store) => store.enabled),
    )
    const [libraryforEveryone, setLibraryforEveryone] = useState(
        useSiteSettingsStore((store) => store.enabled),
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
            button.classList.add('invisible')
        } else {
            button.classList.remove('invisible')
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
            title={__('Extendify Settings', 'extendify-sdk')}
            onRequestClose={closeModal}>
            <ToggleControl
                label={
                    isAdmin
                        ? __('Enable the library for myself', 'extendify-sdk')
                        : __('Enable the library', 'extendify-sdk')
                }
                help={__(
                    'Publish with hundreds of patterns & page layouts',
                    'extendify-sdk',
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
                        )}
                        help={__(
                            'Everyone publishes with patterns & page layouts',
                            'extendify-sdk',
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
