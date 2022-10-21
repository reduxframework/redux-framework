import axios from 'axios'

const Axios = axios.create({
    baseURL: window.extAssistData.root,
    headers: {
        'X-WP-Nonce': window.extAssistData.nonce,
        'X-Requested-With': 'XMLHttpRequest',
        'X-Extendify-Assist': true,
        'X-Extendify': true,
    },
})
Axios.interceptors.request.use(
    (request) => checkDevMode(request),
    (error) => error,
)
Axios.interceptors.response.use((response) =>
    Object.prototype.hasOwnProperty.call(response, 'data')
        ? response.data
        : response,
)

const checkDevMode = (request) => {
    request.headers['X-Extendify-Assist-Dev-Mode'] =
        window.location.search.indexOf('DEVMODE') > -1
    request.headers['X-Extendify-Assist-Local-Mode'] =
        window.location.search.indexOf('LOCALMODE') > -1
    return request
}

export { Axios }
