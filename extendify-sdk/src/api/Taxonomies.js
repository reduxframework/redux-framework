import { Axios as api } from './axios'

export const Taxonomies = {
    get() {
        return api.get('taxonomies')
    },
}
