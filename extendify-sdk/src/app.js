import { rawHandler } from '@wordpress/blocks'
import { render } from '@wordpress/element'
import ExtendifyLibrary from '@extendify/ExtendifyLibrary'
import '@extendify/blocks/blocks'
import '@extendify/buttons'
import '@extendify/listeners'
import { useWantedTemplateStore } from '@extendify/state/Importing'
import { injectTemplateBlocks } from '@extendify/util/templateInjection'

window?.wp?.domReady(() => {
    // Insert into the editor (note: Modal opens in a portal)
    const extendify = Object.assign(document.createElement('div'), {
        id: 'extendify-root',
    })
    document.body.append(extendify)
    render(<ExtendifyLibrary />, extendify)

    // Add an extra div to use for utility modals, etc
    extendify.parentNode.insertBefore(
        Object.assign(document.createElement('div'), {
            id: 'extendify-util',
        }),
        extendify.nextSibling,
    )

    // Insert a template on page load if it exists in localstorage
    // Note 6/28/21 - this was moved to after the render to possibly
    // fix a bug where imports would go from 3->0.
    if (useWantedTemplateStore.getState().importOnLoad) {
        const template = useWantedTemplateStore.getState().wantedTemplate
        setTimeout(() => {
            injectTemplateBlocks(
                rawHandler({ HTML: template.fields.code }),
                template,
            )
        }, 0)
    }

    // Reset template state after checking if we need an import
    useWantedTemplateStore.setState({
        importOnLoad: false,
        wantedTemplate: {},
    })
})
