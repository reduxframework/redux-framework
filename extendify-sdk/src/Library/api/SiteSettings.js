import { Axios as api } from './axios'

export const SiteSettings = {
    getData() {
        return api.get('site-settings')
    },
    setData(data) {
        const formData = new FormData()
        formData.append('data', JSON.stringify(data))
        return api.post('site-settings', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
    },
    updateOption(option, value) {
        return api.post('site-settings/options', { option, value })
    },
}
