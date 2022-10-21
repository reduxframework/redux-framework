import { render } from '@wordpress/element'
import { Assist } from '@assist/Assist'
import { AssistLandingPage } from '@assist/AssistLandingPage'
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
