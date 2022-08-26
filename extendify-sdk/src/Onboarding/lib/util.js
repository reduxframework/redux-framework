/** Takes each possible code section and filters out undefined */
export const findTheCode = (item) =>
    [item?.template?.code, item?.template?.code2].filter(Boolean).join('')

/** Removes any hash or qs values from URL - Airtable adds timestamps */
export const stripUrlParams = (url) => url?.[0]?.url?.split(/[?#]/)?.[0]

/** Lowers the quality of images */
export const lowerImageQuality = (html) => {
    return html.replace(/\w+:\/\/\S*(w=(\d*))&\w+\S*"/g, (url, w, width) =>
        // Could lower the width here if needed
        url.replace(w, 'w=' + Math.floor(Number(width)) + '&q=10'),
    )
}

/** Capitalize first letter of a string */
export const capitalize = (str) =>
    str.charAt(0).toUpperCase() + str.slice(1).toLowerCase()

export const runAtLeastFor = async (functionPromise, time, options) => {
    if (options.dryRun) {
        return new Promise((resolve) => setTimeout(resolve, time))
    }
    const start = Date.now()
    try {
        return await Promise.all([
            await functionPromise(),
            new Promise((resolve) => setTimeout(resolve, time)),
        ])
    } catch (error) {
        console.error(error)
        return await new Promise((resolve) =>
            // Check at least min milliseconds have passed
            setTimeout(resolve, Math.max(0, time - (Date.now() - start))),
        )
    }
}
