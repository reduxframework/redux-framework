import axios from 'axios'
import { Sentry } from '@onboarding/hooks/useSentry'

const Axios = axios.create({
    baseURL: window.extOnbData.root,
    headers: {
        'X-WP-Nonce': window.extOnbData.nonce,
        'X-Requested-With': 'XMLHttpRequest',
        'X-Extendify-Onboarding': true,
        'X-Extendify': true,
    },
})

Axios.interceptors.request.use(
    (request) => checkDevMode(startPerformance(request)),
    (error) => error,
)
Axios.interceptors.response.use(
    (response) => findResponse(measurePerformance(response)),
    (error) => handleErrors(error),
)

const findResponse = (response) => {
    return Object.prototype.hasOwnProperty.call(response, 'data')
        ? response.data
        : response
}

const handleErrors = (error) => {
    if (!error.response) {
        return
    }
    Sentry.captureException(error)
    console.error(error.response)
    return Promise.reject(findResponse(error.response))
}

const checkDevMode = (request) => {
    request.headers['X-Extendify-Onboarding-Dev-Mode'] =
        window.location.search.indexOf('DEVMODE') > -1
    request.headers['X-Extendify-Onboarding-Local-Mode'] =
        window.location.search.indexOf('LOCALMODE') > -1
    return request
}

const startPerformance = (request) => {
    try {
        const endpoint = request?.url?.split('/')?.pop()
        if (!endpoint) return request
        performance.mark(`${endpoint}-extendify`)
    } catch (e) {
        // do nothing
    }
    return request
}
const measurePerformance = (response) => {
    try {
        const url = new URL(response?.request?.responseURL)
        const endpoint = url.pathname?.split('/')?.pop()
        if (!endpoint) return response
        const time = performance.measure(`${endpoint}-extendify`, {
            start: `${endpoint}-extendify`,
            detail: {
                context: { type: 'request' },
                extendify: true,
            },
        })
        const q = new URLSearchParams(window.location.search)
        if (q?.has('performance')) {
            console.info(
                `~> Endpoint /${endpoint} ${
                    // special case to show the site type being queried
                    endpoint === 'styles'
                        ? `(${url.searchParams.get('siteType')}) `
                        : ''
                }in ${time.duration.toFixed()}ms`,
            )
        }
    } catch (e) {
        // do nothing
    }
    return response
}

export { Axios }
