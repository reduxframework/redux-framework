import { Axios as api } from './axios'

export const User = {
    getData() {
        return api.get('user')
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
        return api.post(
            'login', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            },
        )
    },
    register(email) {
        const formData = new FormData()
        formData.append('data', email)
        return api.post(
            'register', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            },
        )
    },
    setData(data) {
        const formData = new FormData()
        formData.append('data', JSON.stringify(data))
        return api.post(
            'user', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            },
        )
    },
    registerMailingList(email) {
        const formData = new FormData()
        formData.append('email', email)
        return api.post(
            'register-mailing-list', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            },
        )
    },
}
