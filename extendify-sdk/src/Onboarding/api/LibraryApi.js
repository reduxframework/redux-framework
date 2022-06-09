import { Axios as api } from './axios'

/* Updates the site type in the Library */
export const updateSiteType = (data) => api.post('library/site-type', data)
