export const maybeHttps = (url) => {
    try {
        const transformed = new URL(url)
        if (window.location.protocol === 'https:') {
            transformed.protocol = 'https:'
        }
        return transformed.toString()
    } catch (e) {
        return url
    }
}
