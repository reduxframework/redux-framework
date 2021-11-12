import { select, dispatch } from '@wordpress/data'

/**
 * Will check if the given CSSRule contains malicious 3rd party URL to secure against XSS
 * @param {CSSRule} rule
 * @return {boolean} isMalicious
 */

function _hasMaliciousURL(rule) {

    let isMalicious = false

    if (!(rule instanceof CSSRule)) return false

    // only allowing airtable API origin
    let allowedOrigins = [ 'https://dl.airtable.com' ]

    let urlRegex = /[(http(s)?)://(www.)?a-zA-Z0-9@:%._+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_+.~#?&//=]*)/g

    let matchedURLS = rule.cssText.match(urlRegex) ?? []

    for (const requestURL of matchedURLS) {

        try {
            let parsedURL = new URL(requestURL)
            let isNotAllowed = !allowedOrigins.includes(parsedURL.origin)

            if (isNotAllowed) {
                isMalicious = true
                break
            }
        } catch (e) {

            // verifying if the regex matched a URL, because regex can mess up due to URL in between other strings
            let isUrl = ['https://', 'http://', '.com'].some(urlPart => requestURL.indexOf(urlPart) !== -1)
            let isVerifiedOrigin = requestURL.indexOf(allowedOrigins[0]) !== -1

            if (isUrl && !isVerifiedOrigin) {
                isMalicious = true
                break
            }

        }

    }

    return isMalicious
}

/**
 * Will inject the given css as an stylesheet in the editor
 * @param {string} css
 * @return {void}
 */

function injectStyleSheetInEditor(css = window.wp.data.select('core/editor').getEditedPostAttribute('meta')?.extendify_custom_stylesheet ?? '') {
    if (typeof css !== 'string') return

    css = css.replace(/(.eplus_styles)/g, '')

    let extendifyRoot = document.querySelector('#extendify-root')
    let styleID = 'extendify-custom-stylesheet'

    if (document.getElementById(styleID)) {
        // stylesheet already exists
        document.getElementById(styleID).innerHTML = css
    } else {
        let styleElement = document.createElement('style')

        styleElement.id = styleID
        styleElement.type = 'text/css'

        styleElement.appendChild(document.createTextNode(css))
        extendifyRoot.appendChild(styleElement)
    }
}

/**
 * Will provide filtered css from the given sheet
 * @param {CSSStyleSheet} sheet
 * @param {string[]} prefix
 * @return {string} css - filtered css
 */

function filterStylesheetWithPrefix(sheet, allowedPrefixes) {
    let filteredCSS = ''

    let isPrefixed = selector => {
        return allowedPrefixes.some(allowedPrefix => selector.startsWith(allowedPrefix))
    }

    for (const rule of sheet?.cssRules ?? []) {
        // if it's a media rule we need to also process the nested rule list
        if (rule instanceof CSSMediaRule) {

            if (_hasMaliciousURL(rule)) continue

            let processedMediaRule = rule?.cssRules ?? []
            let rulesToDelete = [] // because deleting them in the loop can disturb the index

            for (const mediaRuleIndex of Object.keys(processedMediaRule)) {
                let mediaRule = mediaRuleIndex in processedMediaRule
                    ? processedMediaRule[mediaRuleIndex]
                    : {}

                if (!isPrefixed(mediaRule.selectorText)) {
                    rulesToDelete.push(mediaRuleIndex)
                }
            }

            for (const mediaRuleIndexToDelete of rulesToDelete) {
                rule.deleteRule(mediaRuleIndexToDelete)
            }

            filteredCSS += rule.cssText
        }

        if (rule instanceof CSSStyleRule) {
            if (_hasMaliciousURL(rule)) continue

            filteredCSS += isPrefixed(rule.selectorText)
                ? rule.cssText
                : ''
        }
    }

    return filteredCSS
}

/**
 * Listener to enable page template
 */
window._wpLoadBlockEditor && window.addEventListener('extendify-sdk::template-inserted', (event) => {
    const { template } = event.detail
    const wpTemplateName = 'editorplus-template.php'

    // check if the instruction has command to enable page
    if (!template?.fields?.instructions?.includes('enable_page_template')) {
        return
    }

    // Get a list of templates from the editor
    const selector = select('core/editor')
    const availablePageTemplates = selector.getEditorSettings()?.availableTemplates ?? {}
    if (!Object.keys(availablePageTemplates).includes(wpTemplateName)) {
        return
    }

    // Finally, set the template
    dispatch('core/editor').editPost({
        template: wpTemplateName,
    })
})

/**
 * Listener to inject stylesheet
 */
window._wpLoadBlockEditor && window.addEventListener('extendify-sdk::template-inserted', async (event) => {

    // TODO: use better approach which does not use require additional network request

    const { template } = event.detail
    const stylesheetURL = template?.fields?.stylesheet ?? ''

    if (!stylesheetURL) {
        return
    }

    try {
        let generatedCSS = await (await fetch(stylesheetURL)).text()
        let appendedCSS = select('core/editor').getEditedPostAttribute('meta')?.extendify_custom_stylesheet ?? ''

        let createdStyleElement = document.createElement('style')
        let createdStyleID = 'extendify-stylesheet'

        // webkit hack: appending stylesheet to let DOM process rules

        createdStyleElement.id = createdStyleID
        createdStyleElement.type = 'text/css'
        createdStyleElement.appendChild(document.createTextNode(generatedCSS))

        document.querySelector('#extendify-root').appendChild(createdStyleElement)

        let processedStyleSheet = document.getElementById(createdStyleID)

        // disabling the stylesheet
        processedStyleSheet.sheet.disable = true

        // accessing processed CSSStyleSheet
        let filteredCSS = filterStylesheetWithPrefix(processedStyleSheet?.sheet, ['.extendify-', '.eplus_styles', '.eplus-', '[class*="extendify-"]', '[class*="extendify"]'])

        // merging existing styles
        filteredCSS += appendedCSS

        // deleting the generated stylesheet
        processedStyleSheet.parentNode.removeChild(processedStyleSheet)

        // injecting the stylesheet to style the editor view
        injectStyleSheetInEditor(filteredCSS)

        // finally, updating the metadata
        await dispatch('core/editor').editPost({
            meta: {
                extendify_custom_stylesheet: filteredCSS,
            },
        })

    } catch (error) {
        console.error(error)
    }
})

// loading stylesheet in the editor after page load
window._wpLoadBlockEditor && window.wp.domReady(() => {
    setTimeout(() => injectStyleSheetInEditor(), 0)
})

// Quick method to hide the title if the template is active
let extendifyCurrentPageTemplate
window._wpLoadBlockEditor && window.wp.data.subscribe(() => {
    // Nothing changed
    if (extendifyCurrentPageTemplate && extendifyCurrentPageTemplate === window.wp.data.select('core/editor').getEditedPostAttribute('template')) {
        return
    }
    const epTemplateSelected = window.wp.data.select('core/editor').getEditedPostAttribute('template') === 'editorplus-template.php'
    const title = document.querySelector('.edit-post-visual-editor__post-title-wrapper')
    const wrapper = document.querySelector('.editor-styles-wrapper')

    // Too early
    if (!title || !wrapper) {
        return
    }

    if (epTemplateSelected) {
        // GB needs to compute the height first
        Promise.resolve().then(() => title.style.display = 'none')
        wrapper.style.paddingTop = '0'
        wrapper.style.backgroundColor = '#ffffff'
    } else {
        title.style.removeProperty('display')
        wrapper.style.removeProperty('padding-top')
        wrapper.style.removeProperty('background-color')
    }
})
