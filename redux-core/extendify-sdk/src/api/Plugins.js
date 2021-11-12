import { Axios as api } from './axios'

export const Plugins = {
    getInstalled() {
        return api.get('plugins')
    },
    installAndActivate(plugins = []) {
        const formData = new FormData()
        formData.append('plugins', JSON.stringify(plugins))
        return api.post(
            'plugins', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            },
        )
    },
    getActivated() {
        return api.get('active-plugins')
    },
}
