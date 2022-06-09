import { Axios as api } from './axios'

export const getSiteTypes = () => api.get('onboarding/site-types')

export const getStyles = (data) =>
    api.get('onboarding/styles', { params: data })

export const getGoals = () => api.get('onboarding/goals')
export const getSuggestedPlugins = () => api.get('onboarding/suggested-plugins')

export const getLayoutTypes = () => api.get('onboarding/layout-types')

export const getTemplate = (data) =>
    api.get('onboarding/template', { params: data })

export const createOrder = (data) => api.post('onboarding/orders', data)
