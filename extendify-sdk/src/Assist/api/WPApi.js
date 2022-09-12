import { Axios as api } from './axios'

export const getLaunchPages = () => api.get('assist/get-launch-pages')
