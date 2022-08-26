import { render } from '@wordpress/element'
import { LandingPage } from '@assist/LandingPage'

// Render the landing page if we are on it
const mainPage = document.getElementById('extendify-assist-landing-page')
mainPage && render(<LandingPage />, mainPage)
