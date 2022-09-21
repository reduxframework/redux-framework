import { Axios as api } from './axios'

export const getLaunchPages = () => api.get('assist/launch-pages')
