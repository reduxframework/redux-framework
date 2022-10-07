import { render } from '@wordpress/element'
import { AssistLandingPage } from '@assist/AssistLandingPage'
import { AdminNotice } from '@assist/components/AdminNotice'

// Disable Assist while Launch is running
const q = new URLSearchParams(window.location.search)
const launchActive = ['onboarding'].includes(q.get('extendify'))
const launchCompleted = window.extAssistData.launchCompleted
const devbuild = window.extAssistData.devbuild

const assistPage = document.getElementById('extendify-assist-landing-page')
const dashboard = document.getElementById('dashboard-widgets-wrap')

// Assist landing page
if (!launchActive && assistPage) {
    render(<AssistLandingPage />, assistPage)
}

// Launch admin notice - show on devmode too
if (dashboard && (!launchCompleted || devbuild)) {
    const launchAdminNotice = Object.assign(document.createElement('div'), {
        className: 'extendify-assist',
    })
    // Hide the welcome notice if we are going to display ours
    document.getElementById('welcome-panel')?.remove()
    dashboard.before(launchAdminNotice)
    render(<AdminNotice />, launchAdminNotice)
}
