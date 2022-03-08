import { Axios as api } from './axios'

export const User = {
    async getData() {
        // Zustand changed their persist middleware to bind to the store
        // so api was undefined here. That's why using fetch for this one request.
        const data = await fetch(`${window.extendifyData.root}/user`, {
            method: 'GET',
            headers: {
                'X-WP-Nonce': window.extendifyData.nonce,
                'X-Requested-With': 'XMLHttpRequest',
                'X-Extendify': true,
            },
        })
        return await data.json()
    },
    getMeta(key) {
        return api.get('user-meta', {
            params: {
                key,
            },
        })
    },
    authenticate(email, key) {
        const formData = new FormData()
        formData.append('email', email)
        formData.append('key', key)
        return api.post('login', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
    },
    register(email) {
        const formData = new FormData()
        formData.append('data', email)
        return api.post('register', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
    },
    setData(data) {
        const formData = new FormData()
        formData.append('data', JSON.stringify(data))
        return api.post('user', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
    },
    deleteData() {
        return api.post('clear-user')
    },
    registerMailingList(email) {
        const formData = new FormData()
        formData.append('email', email)
        return api.post('register-mailing-list', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
    },
    allowedImports() {
        return api.get('max-free-imports')
    },
}
