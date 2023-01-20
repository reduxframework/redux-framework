import { render } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { Assist } from '@assist/Assist'
import { AssistLandingPage } from '@assist/AssistLandingPage'
import { AssistTaskbar } from '@assist/AssistTaskbar'
import { TaskBadge } from '@assist/components/TaskBadge'
import { AdminNotice } from '@assist/notices/AdminNotice'
import './app.css'

// Disable Assist while Launch is running
const q = new URLSearchParams(window.location.search)
const launchActive = ['page'].includes(q.get('extendify-launch'))
const launchCompleted = window.extAssistData.launchCompleted
const devbuild = window.extAssistData.devbuild

const assistPage = document.getElementById('extendify-assist-landing-page')
const dashboard = document.getElementById('dashboard-widgets-wrap')

// Assist landing page
if (!launchActive && assistPage) {
    // append skip link to get here
    document
        .querySelector('.screen-reader-shortcut')
        .insertAdjacentHTML(
            'afterend',
            `<a href="#extendify-assist-landing-page" class="screen-reader-shortcut">${__(
                'Skip to Assist',
                'extendify',
            )}</a>`,
        )
    render(<AssistLandingPage />, assistPage)
}

// Check if they dismissed the notiec already
const dismissed = window.extAssistData.dismissedNotices.find(
    (notice) => notice.id === 'extendify-launch',
)
// Launch admin notice - show on devmode too
if (dashboard && !dismissed && (!launchCompleted || devbuild)) {
    const launchAdminNotice = Object.assign(document.createElement('div'), {
        className: 'extendify-assist',
    })
    // Hide the welcome notice if we are going to display ours
    document.getElementById('welcome-panel')?.remove()
    dashboard.before(launchAdminNotice)
    render(<AdminNotice />, launchAdminNotice)
}

if (!launchActive) {
    const assist = Object.assign(document.createElement('div'), {
        className: 'extendify-assist',
    })
    document.body.append(assist)
    render(<Assist />, assist)
}

if (!launchActive) {
    document
        .querySelector(
            '#toplevel_page_extendify-admin-page.wp-has-current-submenu',
        )
        ?.classList.add('current')
    document
        .querySelectorAll('.extendify-assist-badge-count')
        ?.forEach((el) => render(<TaskBadge />, el))
}

if (!launchActive) {
    const taskbar = Object.assign(document.createElement('li'), {
        id: 'wp-admin-bar-extendify-assist-link',
    })
    document.querySelector('#wp-admin-bar-my-account')?.after(taskbar)
    render(<AssistTaskbar />, taskbar)
}
