import axios from 'axios'

const Axios = axios.create({
    baseURL: window.extOnbData.root,
    headers: {
        'X-WP-Nonce': window.extOnbData.nonce,
        'X-Requested-With': 'XMLHttpRequest',
        'X-Extendify-Onboarding': true,
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
    return Promise.reject(findResponse(error.response))
}

function checkDevMode(request) {
    request.headers['X-Extendify-Onboarding-Dev-Mode'] =
        window.location.search.indexOf('DEVMODE') > -1
    request.headers['X-Extendify-Onboarding-Local-Mode'] =
        window.location.search.indexOf('LOCALMODE') > -1
    return request
}

Axios.interceptors.response.use(
    (response) => findResponse(response),
    (error) => handleErrors(error),
)
Axios.interceptors.request.use(
    (request) => checkDevMode(request),
    (error) => error,
)

export { Axios }
