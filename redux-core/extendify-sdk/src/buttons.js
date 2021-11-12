import { __ } from '@wordpress/i18n'
import { renderToString } from '@wordpress/element'
import { registerPlugin } from '@wordpress/plugins'
import { openModal } from './util/general'
import { PluginSidebarMoreMenuItem } from '@wordpress/edit-post'
import { User } from './api/User'

const openLibrary = (event) => {
    openModal(event.target.closest('[data-extendify-identifier]')?.dataset?.extendifyIdentifier)
}

// This returns true if the user object is null (Library never opened), or if it's enabled in the user settings
const isLibraryEnabled = () => window.extendifySdkData.user === null || window.extendifySdkData?.user?.state?.enabled

const mainButton = <div id="extendify-templates-inserter">
    <button
        style="background:#D9F1EE;color:#1e1e1e;border:1px solid #949494;font-weight:bold;font-size:14px;padding:8px;margin-right:8px"
        type="button"
        data-extendify-identifier="main-button"
        id="extendify-templates-inserter-btn"
        className="components-button">
        <svg style="margin-right:0.5rem" width="20" height="20" viewBox="0 0 103 103" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect y="25.75" width="70.8125" height="77.25" fill="#000000"/>
            <rect x="45.0625" width="57.9375" height="57.9375" fill="#37C2A2"/>
        </svg>
        {__('Library', 'extendify-sdk')}
    </button>
</div>

// Add the MAIN button when Gutenberg is available and ready
window._wpLoadBlockEditor && window.wp.data.subscribe(() => {
    setTimeout(() => {
        // Redundant extra check added because of a bug where the above check wasn't working
        if (!isLibraryEnabled()) {
            return
        }
        if (document.getElementById('extendify-templates-inserter-btn')) {
            return
        }
        if (!document.querySelector('.edit-post-header-toolbar')) {
            return
        }
        document.querySelector('.edit-post-header-toolbar').insertAdjacentHTML('beforeend', renderToString(mainButton))
        document.getElementById('extendify-templates-inserter-btn').addEventListener('click', openLibrary)
    }, 0)
})

// The CTA button inside patterns
window._wpLoadBlockEditor && window.wp.data.subscribe(() => {
    setTimeout(() => {
        // Redundant extra check added because of a bug where the above check wasn't working
        if (!isLibraryEnabled()) {
            return
        }
        if (!document.querySelector('[id$=patterns-view]')) {
            return
        }
        if (document.getElementById('extendify-cta-button')) {
            return
        }
        const ctaButton = <div>
            <button
                id="extendify-cta-button"
                style="margin:1rem 1rem 0"
                data-extendify-identifier="patterns-cta"
                className="components-button is-secondary">
                {__('Discover more patterns in Extendify Library', 'extendify-sdk')}
            </button>
        </div>
        document.querySelector('[id$=patterns-view]').insertAdjacentHTML('afterbegin', renderToString(ctaButton))
        document.getElementById('extendify-cta-button').addEventListener('click', openLibrary)
    }, 0)
})

// The right dropdown side menu
const SideMenuButton = () => <PluginSidebarMoreMenuItem
    data-extendify-identifier="sidebar-button"
    onClick={openLibrary}
    icon={
        <span className="components-menu-items__item-icon">
            <svg width="20" height="20" viewBox="0 0 103 103" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect y="25.75" width="70.8125" height="77.25" fill="#000000"/>
                <rect x="45.0625" width="57.9375" height="57.9375" fill="#37C2A2"/>
            </svg>
        </span>
    }
>
    {__('Library', 'extendify-sdk')}
</PluginSidebarMoreMenuItem>
window._wpLoadBlockEditor && isLibraryEnabled() && registerPlugin('extendify-temps-more-menu-trigger', {
    render: SideMenuButton,
})

// This will add a button to enable or disable the library button
const LibraryEnableDisable = () => <PluginSidebarMoreMenuItem
    onClick={async () => {
        // This works even when the Library hasn't been opened yet
        // because User.getData() will build a barebones User object
        let userData = await User.getData()
        userData = JSON.parse(userData)
        userData.state.enabled = !isLibraryEnabled()
        await User.setData(JSON.stringify(Object.assign({}, userData)))
        location.reload()
    }}
    icon={<></>}
>
    {isLibraryEnabled()
        ? __('Disable Extendify', 'extendify-sdk')
        : __('Enable Extendify', 'extendify-sdk')}
</PluginSidebarMoreMenuItem>

// Load this button always, which is used to enable or disable
window._wpLoadBlockEditor && registerPlugin('extendify-settings-enable-disable', {
    render: LibraryEnableDisable,
})
