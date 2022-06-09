import domReady from '@wordpress/dom-ready'
import { render } from '@wordpress/element'
import { Onboarding } from '@onboarding/Onboarding'

const extendify = Object.assign(document.createElement('div'), {
    id: 'extendify-onboarding-root',
    className: 'extendify-onboarding',
})
document.body.append(extendify)
domReady(() => {
    window._wpLoadBlockEditor && render(<Onboarding />, extendify)
})
