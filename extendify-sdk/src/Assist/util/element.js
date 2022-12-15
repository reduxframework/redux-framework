import { kebabCase } from 'lodash'

// Without this there are just too many to process.
// Some of these are required though to render properly.
const vendorAllowList = [
    '-webkit-mask-image',
    '-webkit-mask-size',
    '-webkit-mask-repeat',
    '-webkit-mask-position',
    '-webkit-mask-origin',
    '-webkit-mask-clip',
]
export const copyNodeStyle = (
    currentElement,
    clonedElement,
    options,
    depth = 0,
) => {
    const computedStyle = window.getComputedStyle(currentElement)
    Array.from(computedStyle).forEach((key) => {
        // Ignore some properties prefixed with vendor prefixes
        if (key.startsWith('-') && !vendorAllowList.includes(key)) return
        clonedElement.style.setProperty(
            key,
            computedStyle.getPropertyValue(key),
            computedStyle.getPropertyPriority(key),
        )
    })

    // Remove pointer events
    clonedElement.style.pointerEvents = 'none'

    // This is "expensive" so keep it off by default
    if (options?.includePsuedoDepth > depth) {
        // This copies ::before to child elements
        const identifier = 'id' + Math.round(performance.now())
        // Create a stylesheet with all the before values
        const style = document.createElement('style')
        // Add a class of the identifier
        clonedElement.classList.add(identifier)
        style.innerHTML = `.${identifier}::before { ${Object.entries(
            window.getComputedStyle(currentElement, 'before'),
        )
            // filter out items with numeric keys
            .filter(([key]) => !Number.isInteger(Number(key)))
            // Normalize the key names
            .map(([key, value]) => `${kebabCase(key)}: ${value};`)
            .join(' ')} }`
        document.getElementsByTagName('head')[0].appendChild(style)
    }

    // Recursively do the same for all children
    // TODO: if there's a performance hit on an item, use depth (like above)
    const children = currentElement?.querySelectorAll('*')
    const clonedChildren = clonedElement?.querySelectorAll('*')

    children.forEach((_, i) => {
        copyNodeStyle(children[i], clonedChildren[i], options, depth + 1)
    })
}
