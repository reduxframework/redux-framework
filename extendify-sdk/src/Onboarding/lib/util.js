import { pingServer } from '@onboarding/api/DataApi'

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

/** Will ping every 1s until we get a 200 response from the server  */
export const waitFor200Response = async () => {
    try {
        // This will error if not 200
        await pingServer()
        return true
    } catch (error) {
        //
    }
    await new Promise((resolve) => setTimeout(resolve, 1000))
    return waitFor200Response()
}
