import { __ } from '@wordpress/i18n'
import { renderToString, render } from '@wordpress/element'
import { registerPlugin } from '@wordpress/plugins'
import { openModal } from './util/general'
import { PluginSidebarMoreMenuItem } from '@wordpress/edit-post'
import { Icon } from '@wordpress/icons'
import { brandMark } from './components/icons/'
import LibraryAccessModal from './components/LibraryAccessModal'

const openLibrary = (event) => {
    openModal(
        event.target.closest('[data-extendify-identifier]')?.dataset
            ?.extendifyIdentifier,
    )
}

// This returns true if the user object is null (Library never opened), or if it's enabled in the user settings
const isAdmin = () =>
    window.extendifyData.user === null ||
    window.extendifyData?.user?.state?.isAdmin
const isGlobalLibraryEnabled = () =>
    window.extendifyData.sitesettings === null ||
    window.extendifyData?.sitesettings?.state?.enabled
const isLibraryEnabled = () =>
    window.extendifyData.user === null
        ? isGlobalLibraryEnabled()
        : window.extendifyData?.user?.state?.enabled

const mainButton = (
    <div id="extendify-templates-inserter" className="extendify">
        <button
            style="padding:4px 12px; height: 34px;"
            type="button"
            data-extendify-identifier="main-button"
            id="extendify-templates-inserter-btn"
            className="components-button bg-wp-theme-500 hover:bg-wp-theme-600 border-color-wp-theme-500 text-white ml-1">
            <Icon icon={brandMark} size={24} className="-ml-1 mr-1" />
            {__('Library', 'extendify')}
        </button>
    </div>
)

// Add the MAIN button when Gutenberg is available and ready
if (window._wpLoadBlockEditor) {
    const finish = window.wp.data.subscribe(() => {
        requestAnimationFrame(() => {
            if (!isGlobalLibraryEnabled() && !isAdmin()) {
                return
            }

            // Redundant extra check added because of a bug where the above check wasn't working
            if (document.getElementById('extendify-templates-inserter-btn')) {
                return
            }
            if (!document.querySelector('.edit-post-header-toolbar')) {
                return
            }
            document
                .querySelector('.edit-post-header-toolbar')
                .insertAdjacentHTML('beforeend', renderToString(mainButton))
            document
                .getElementById('extendify-templates-inserter-btn')
                .addEventListener('click', openLibrary)
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
    const finish = window.wp.data.subscribe(() => {
        requestAnimationFrame(() => {
            // Redundant extra check added because of a bug where the above check wasn't working
            if (!isGlobalLibraryEnabled() && !isAdmin()) {
                return
            }
            if (!document.querySelector('[id$=patterns-view]')) {
                return
            }
            if (document.getElementById('extendify-cta-button')) {
                return
            }
            const ctaButton = (
                <div>
                    <button
                        id="extendify-cta-button"
                        style="margin:1rem 1rem 0;width: calc(100% - 2rem);justify-content: center;"
                        data-extendify-identifier="patterns-cta"
                        className="components-button is-secondary">
                        {__(
                            'Discover patterns in Extendify Library',
                            'extendify',
                        )}
                    </button>
                </div>
            )
            document
                .querySelector('[id$=patterns-view]')
                .insertAdjacentHTML('afterbegin', renderToString(ctaButton))
            document
                .getElementById('extendify-cta-button')
                .addEventListener('click', openLibrary)
            finish()
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
