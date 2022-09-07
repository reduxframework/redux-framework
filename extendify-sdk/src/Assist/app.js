import { render } from '@wordpress/element'
import { AssistLandingPage } from '@assist/AssistLandingPage'

// Render the landing page if we are on it
const mainPage = document.getElementById('extendify-assist-landing-page')
mainPage && render(<AssistLandingPage />, mainPage)
