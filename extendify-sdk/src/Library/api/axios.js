import axios from 'axios'
import { useUserStore } from '@library/state/User'

const Axios = axios.create({
    baseURL: window.extendifyData.root,
    headers: {
        'X-WP-Nonce': window.extendifyData.nonce,
        'X-Requested-With': 'XMLHttpRequest',
        'X-Extendify-Library': true,
        'X-Extendify': true,
    },
})

function findResponse(response) {
    return Object.prototype.hasOwnProperty.call(response, 'data')
        ? response.data
        : response
}

function handleErrors(error) {
    if (!error.response) {
        return
    }
    console.error(error.response)
    // TODO: add a global error message system
    return Promise.reject(findResponse(error.response))
}

function addDefaults(request) {
    const userState = useUserStore.getState()
    const remainingImports = userState.apiKey
        ? 'unlimited'
        : userState.remainingImports()
    if (request.data) {
        request.data.remaining_imports = remainingImports
        request.data.entry_point = userState.entryPoint
        request.data.total_imports = userState.imports
    }
    return request
}

function checkDevMode(request) {
    request.headers['X-Extendify-Dev-Mode'] =
        window.location.search.indexOf('DEVMODE') > -1
    request.headers['X-Extendify-Local-Mode'] =
        window.location.search.indexOf('LOCALMODE') > -1
    return request
}

function checkForSoftError(response) {
    if (Object.prototype.hasOwnProperty.call(response, 'soft_error')) {
        window.dispatchEvent(
            new CustomEvent('extendify::softerror-encountered', {
                detail: response.soft_error,
                bubbles: true,
            }),
        )
    }
    return response
}

Axios.interceptors.response.use(
    (response) => checkForSoftError(findResponse(response)),
    (error) => handleErrors(error),
)

Axios.interceptors.request.use(
    (request) => checkDevMode(addDefaults(request)),
    (error) => error,
)

export { Axios }
