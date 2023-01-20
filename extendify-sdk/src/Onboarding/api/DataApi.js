import { getHeadersAndFooters } from './WPApi'
import { Axios as api } from './axios'

export const getSiteTypes = () => api.get('onboarding/site-types')
export const getStylesList = () => api.get('onboarding/styles-list')

export const getStyles = async (data) => {
    // First get the header and footer code
    const styles = await api.get('onboarding/styles', { params: data })
    const { headers, footers } = await getHeadersAndFooters()
    if (!styles?.data?.length) {
        throw new Error('Could not get styles')
    }
    return {
        data: styles.data.map((style) => {
            const header = headers?.find(
                (h) => h?.slug === style?.headerSlug ?? 'header',
            )
            const footer = footers?.find(
                (f) => f?.slug === style?.footerSlug ?? 'footer',
            )

            return {
                ...style,
                headerCode: header?.content?.raw?.trim() ?? '',
                footerCode: footer?.content?.raw?.trim() ?? '',
            }
        }),
    }
}

export const getGoals = () => api.get('onboarding/goals')
export const getSuggestedPlugins = () => api.get('onboarding/suggested-plugins')

export const getLayoutTypes = () => api.get('onboarding/layout-types')

export const getTemplate = (data) =>
    api.get('onboarding/template', { params: data })

export const getExitQuestions = () =>
    api.get('onboarding/exit-questions', {
        timeout: 1500,
    })

export const pingServer = () => api.get('onboarding/ping')
