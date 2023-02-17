export const waitUntilExists = (selector) => {
    return new Promise((resolve) => {
        const interval = setInterval(() => {
            if (document.querySelector(selector)) {
                clearInterval(interval)
                resolve()
            }
        }, 50)
    })
}
export const waitUntilGone = (selector) => {
    return new Promise((resolve) => {
        const interval = setInterval(() => {
            if (!document.querySelector(selector)) {
                clearInterval(interval)
                resolve()
            }
        }, 50)
    })
}
