import { render } from '@wordpress/element'
import { Onboarding } from '@onboarding/Onboarding'
import './app.css'

const launch = document.getElementById('extendify-onboarding-page')
launch && render(<Onboarding />, launch)
