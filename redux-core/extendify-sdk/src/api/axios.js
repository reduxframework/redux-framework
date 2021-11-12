import axios from 'axios'
import { useUserStore } from '../state/User'

const Axios = axios.create({
    baseURL: window.extendifySdkData.root,
    headers: {
        'X-WP-Nonce': window.extendifySdkData.nonce,
        'X-Requested-With': 'XMLHttpRequest',
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
    if (request.data) {
        request.data.remaining_imports = useUserStore.getState().remainingImports()
        request.data.entry_point = useUserStore.getState().entryPoint
        request.data.total_imports = useUserStore.getState().imports
    }
    return request
}

function checkDevMode(request) {
    request.headers['X-Extendify-Dev-Mode'] = window.location.search.indexOf('DEVMODE') > -1
    request.headers['X-Extendify-Local-Mode'] = window.location.search.indexOf('LOCALMODE') > -1
    return request
}

function checkForSoftError(response) {
    if (Object.prototype.hasOwnProperty.call(response, 'soft_error')) {
        window.dispatchEvent(new CustomEvent('extendify-sdk::softerror-encountered', {
            detail: response.soft_error,
            bubbles: true,
        }))
    }
    return response
}

Axios.interceptors.response.use((response) => checkForSoftError(findResponse(response)),
    (error) => handleErrors(error))

// TODO: setup a pipe function instead of this nested pattern
Axios.interceptors.request.use((request) => checkDevMode(addDefaults(request)),
    (error) => error)

export { Axios }
