import { Axios as api } from './axios'

export const getLaunchPages = () => api.get('assist/launch-pages')

export const updateOption = (option, value) =>
    api.post('assist/options', { option, value })

export const getOption = async (option) => {
    const { data } = await api.get('assist/options', {
        params: { option },
    })
    return data
}

export const getActivePlugins = () => api.get('assist/active-plugins')
