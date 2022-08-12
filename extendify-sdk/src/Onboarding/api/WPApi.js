import { __, sprintf } from '@wordpress/i18n'
import { Axios as api } from './axios'

export const parseThemeJson = (themeJson) =>
    api.post('onboarding/parse-theme-json', { themeJson })

export const updateOption = (option, value) =>
    api.post('onboarding/options', { option, value })

export const getOption = async (option) => {
    const { data } = await api.get('onboarding/options', {
        params: { option },
    })
    return data
}

export const createPage = (pageData) =>
    api.post(`${window.extOnbData.wpRoot}wp/v2/pages`, pageData)

export const trashPost = (postId, postType) =>
    api.delete(`${window.extOnbData.wpRoot}wp/v2/${postType}s/${postId}`)

export const getPost = (postSlug, type = 'post') =>
    api.get(`${window.extOnbData.wpRoot}wp/v2/${type}s?slug=${postSlug}`)

export const installPlugin = async (plugin) => {
    // Fail silently if no slug is provided
    if (!plugin?.wordpressSlug) return

    try {
        // Install plugin and try to activate it.
        const response = await api.post(
            `${window.extOnbData.wpRoot}wp/v2/plugins`,
            {
                slug: plugin.wordpressSlug,
                status: 'active',
            },
        )
        if (!response.ok) return response
    } catch (e) {
        // Fail gracefully for now
    }

    try {
        // Try and activate it if the above fails
        return await activatePlugin(plugin)
    } catch (e) {
        // Fail gracefully for now
    }
}

export const activatePlugin = async (plugin) => {
    const endpoint = `${window.extOnbData.wpRoot}wp/v2/plugins`
    const response = await api.get(`${endpoint}?search=${plugin.wordpressSlug}`)
    const pluginSlug = response?.[0]?.plugin
    if (!pluginSlug) {
        throw new Error('Plugin not found')
    }
    // Attempt to activate the plugin with the slug we found
    return await api.post(`${endpoint}/${pluginSlug}`, { status: 'active' })
}

export const updateTemplatePart = (part, content) =>
    api.post(`${window.extOnbData.wpRoot}wp/v2/template-parts/${part}`, {
        slug: `${part}`,
        theme: 'extendable',
        type: 'wp_template_part',
        status: 'publish',
        description: sprintf(
            __('Added by %s', 'extendify'),
            'Extendify Launch',
        ),
        content,
    })

export const getHeadersAndFooters = async () => {
    let patterns = await getTemplateParts()
    patterns = patterns?.filter((p) => p.theme === 'extendable')
    const headers = patterns?.filter((p) => p?.slug?.includes('header'))
    const footers = patterns?.filter((p) => p?.slug?.includes('footer'))
    return { headers, footers }
}

const getTemplateParts = () =>
    api.get(window.extOnbData.wpRoot + 'wp/v2/template-parts')

export const getThemeVariations = () =>
    api.get(
        window.extOnbData.wpRoot +
            'wp/v2/global-styles/themes/extendable/variations',
    )

export const updateThemeVariation = (id, variation) =>
    api.post(`${window.extOnbData.wpRoot}wp/v2/global-styles/${id}`, {
        id,
        settings: variation.settings,
        styles: variation.styles,
    })
