import { __ } from '@wordpress/i18n'
import { render } from '@wordpress/element'
import { registerPlugin } from '@wordpress/plugins'
import { PluginSidebarMoreMenuItem } from '@wordpress/edit-post'
import { Icon } from '@wordpress/icons'
import { brandMark } from './components/icons/'
import LibraryAccessModal from './components/LibraryAccessModal'
import { CtaButton, MainButton } from './components/MainButtons'

const userState = window.extendifyData?.user?.state
const isAdmin = () => window.extendifyData.user === null || userState?.isAdmin
const isGlobalLibraryEnabled = () =>
    window.extendifyData.sitesettings === null ||
    window.extendifyData?.sitesettings?.state?.enabled
const isLibraryEnabled = () =>
    window.extendifyData.user === null
        ? isGlobalLibraryEnabled()
        : userState?.enabled

// Add the MAIN button when Gutenberg is available and ready
if (window._wpLoadBlockEditor) {
    const finish = window.wp.data.subscribe(() => {
        requestAnimationFrame(() => {
            if (!isGlobalLibraryEnabled() && !isAdmin()) {
                return
            }
            if (document.getElementById('extendify-templates-inserter')) {
                return
            }
            if (!document.querySelector('.edit-post-header-toolbar')) {
                return
            }
            const buttonContainer = Object.assign(
                document.createElement('div'),
                { id: 'extendify-templates-inserter' },
            )
            document
                .querySelector('.edit-post-header-toolbar')
                .append(buttonContainer)
            render(<MainButton />, buttonContainer)

            if (!isLibraryEnabled()) {
                document
                    .getElementById('extendify-templates-inserter-btn')
                    .classList.add('invisible')
            }
            finish()
        })
    })
}

// The CTA button inside patterns
if (window._wpLoadBlockEditor) {
    window.wp.data.subscribe(() => {
        requestAnimationFrame(() => {
            if (!isGlobalLibraryEnabled() && !isAdmin()) {
                return
            }
            if (!document.querySelector('[id$=patterns-view]')) {
                return
            }
            if (document.getElementById('extendify-cta-button')) {
                return
            }
            const ctaButtonContainer = Object.assign(
                document.createElement('div'),
                { id: 'extendify-cta-button-container' },
            )

            document
                .querySelector('[id$=patterns-view]')
                .prepend(ctaButtonContainer)
            render(<CtaButton />, ctaButtonContainer)
        })
    })
}

// This will add a button to enable or disable the library button
const LibraryEnableDisable = () => {
    function setOpenSiteSettingsModal() {
        const util = document.getElementById('extendify-util')
        render(<LibraryAccessModal />, util)
    }

    return (
        <>
            <PluginSidebarMoreMenuItem
                onClick={setOpenSiteSettingsModal}
                icon={<Icon icon={brandMark} size={24} />}>
                {' '}
                {__('Extendify', 'extendify')}
            </PluginSidebarMoreMenuItem>
        </>
    )
}

// Load this button always, which is used to enable or disable
window._wpLoadBlockEditor &&
    registerPlugin('extendify-settings-enable-disable', {
        render: LibraryEnableDisable,
    })
