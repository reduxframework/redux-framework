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

export { Axios }
