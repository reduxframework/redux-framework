import ExtendifyLibrary from './ExtendifyLibrary'
import { render } from '@wordpress/element'
import { useWantedTemplateStore } from './state/Importing'
import { injectTemplate } from './util/templateInjection'
import './buttons'
import './listeners'

window._wpLoadBlockEditor && window.wp.domReady(() => {
    // Insert into the editor (note: Modal opens in a portal)
    const extendify = Object.assign(document.createElement('div'), { id: 'extendify-root' })
    document.body.append(extendify)
    render(<ExtendifyLibrary/>, extendify)

    // Add an extra div to use for utility modals, etc
    extendify.parentNode.insertBefore(Object.assign(document.createElement('div'), { id: 'extendify-util' }),
        extendify.nextSibling)

    // Insert a template on page load if it exists in localstorage
    // Note 6/28/21 - this was moved to after the render to possibly
    // fix a bug where imports would go from 3->0.
    if (useWantedTemplateStore.getState().importOnLoad) {
        const template = useWantedTemplateStore.getState().wantedTemplate
        setTimeout(() => { injectTemplate(template) }, 0)
    }

    // Reset template state after checking if we need an import
    useWantedTemplateStore.setState({
        importOnLoad: false,
        wantedTemplate: {},
    })
})
