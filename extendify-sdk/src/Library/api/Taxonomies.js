import { Axios as api } from './axios'

export const Taxonomies = {
    async get() {
        return await api.get('taxonomies')
    },
}
