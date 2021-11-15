import { __ } from '@wordpress/i18n'
import { renderToString, render } from '@wordpress/element'
import { registerPlugin } from '@wordpress/plugins'
import { openModal } from './util/general'
import { PluginSidebarMoreMenuItem } from '@wordpress/edit-post'
import { Icon } from '@wordpress/icons'
import { brandMark } from './components/icons/'
import LibraryAccessModal from './components/LibraryAccessModal'

const openLibrary = (event) => {
    openModal(event.target.closest('[data-extendify-identifier]')?.dataset?.extendifyIdentifier)
}

// This returns true if the user object is null (Library never opened), or if it's enabled in the user settings
const isAdmin = () => window.extendifySdkData.user === null || window.extendifySdkData?.user?.state?.isAdmin
const isGlobalLibraryEnabled = () => window.extendifySdkData.sitesettings === null || window.extendifySdkData?.sitesettings?.state?.enabled
const isLibraryEnabled = () => window.extendifySdkData.user === null ? isGlobalLibraryEnabled() : window.extendifySdkData?.user?.state?.enabled

const mainButton = <div id="extendify-templates-inserter" className="extendify-sdk">
    <button
        style="background:#D9F1EE;color:#1e1e1e;border:1px solid #949494 !important;font-weight:bold;font-size:14px;padding:8px;margin-right:8px"
        type="button"
        data-extendify-identifier="main-button"
        id="extendify-templates-inserter-btn"
        className="components-button">
        <Icon 
            icon={ brandMark } 
            size={ 24 } 
            className="-ml-1 mr-1" />
        {__('Library', 'extendify-sdk')}
    </button>
</div>

// Add the MAIN button when Gutenberg is available and ready
window._wpLoadBlockEditor && window.wp.data.subscribe(() => {

    setTimeout(() => {

        if(!isGlobalLibraryEnabled() && !isAdmin()){
            return
        }

        // Redundant extra check added because of a bug where the above check wasn't working
        if (document.getElementById('extendify-templates-inserter-btn')) {
            return
        }
        if (!document.querySelector('.edit-post-header-toolbar')) {
            return
        }
        document.querySelector('.edit-post-header-toolbar').insertAdjacentHTML('beforeend', renderToString(mainButton))
        document.getElementById('extendify-templates-inserter-btn').addEventListener('click', openLibrary)
        if (!isLibraryEnabled()) {
            document.getElementById('extendify-templates-inserter-btn').classList.add('invisible')
        }
    }, 0)
})

// The CTA button inside patterns
window._wpLoadBlockEditor && window.wp.data.subscribe(() => {
    setTimeout(() => {
        // Redundant extra check added because of a bug where the above check wasn't working
        if(!isGlobalLibraryEnabled() && !isAdmin()){
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

// This will add a button to enable or disable the library button
const LibraryEnableDisable = () => {

    function setOpenSiteSettingsModal(){
        const util = document.getElementById('extendify-util')
        render(<LibraryAccessModal/>, util)
    }

    return <>
        <PluginSidebarMoreMenuItem
            onClick={setOpenSiteSettingsModal}
            icon={ <Icon icon={ brandMark } size={ 24 } /> }
        >  { __('Extendify', 'extendify-sdk') }
        </PluginSidebarMoreMenuItem>
    </>
}

// Load this button always, which is used to enable or disable
window._wpLoadBlockEditor && registerPlugin('extendify-settings-enable-disable', {
    render: LibraryEnableDisable,
})
