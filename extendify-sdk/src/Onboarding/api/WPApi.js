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

export const createPage = async (pageData) => {
    const options = {
        method: 'POST',
        headers: {
            'Content-type': 'application/json',
            'X-WP-Nonce': window.extOnbData.nonce,
        },
        body: JSON.stringify(pageData),
    }
    const url = `${window.extOnbData.wpRoot}wp/v2/pages`
    const response = await fetch(url, options)
    const data = await response.json()
    return data
}

export const installPlugin = async (slug) => {
    // Fail silently if no slug is provided
    if (!slug) return
    const headers = {
        'Content-type': 'application/json',
        'X-WP-Nonce': window.extOnbData.nonce,
    }
    const url = `${window.extOnbData.wpRoot}wp/v2/plugins`
    const response = await fetch(url, {
        method: 'POST',
        headers,
        body: JSON.stringify({ slug, status: 'active' }),
    })
    if (response.status >= 200 && response.status < 300) {
        return await response.json()
    }
    // The above could fail if the plugin is already installed
    // But we at least want to try and activate it if that's the case
    return await activatePlugin(slug)
}

export const activatePlugin = async (slug) => {
    const headers = {
        'Content-type': 'application/json',
        'X-WP-Nonce': window.extOnbData.nonce,
    }
    const endpoint = `${window.extOnbData.wpRoot}wp/v2/plugins`
    const response = await fetch(`${endpoint}?search=${slug}`, { headers })
    const plugin = (await response.json())?.[0]?.plugin
    const response2 = await fetch(`${endpoint}/${plugin}`, {
        method: 'POST',
        headers,
        body: JSON.stringify({ status: 'active' }),
    })
    return await response2.json()
}

export const updateTemplatePart = async (part, content) => {
    const options = {
        method: 'POST',
        headers: {
            'Content-type': 'application/json',
            'X-WP-Nonce': window.extOnbData.nonce,
        },
        body: JSON.stringify({
            slug: `${part}`,
            theme: 'extendable',
            type: 'wp_template_part',
            status: 'publish',
            description: sprintf(
                __('Added by %s', 'extendify'),
                'Extendify Launch',
            ),
            content,
        }),
    }

    try {
        const url = `${window.extOnbData.wpRoot}wp/v2/template-parts/${part}`
        const response = await fetch(url, options)
        const data = await response.json()
        return data
    } catch (e) {
        // Fail gracefully for now
    }
}
export const getHeadersAndFooters = async () => {
    let patterns = await getTemplateParts()
    patterns = patterns?.filter((p) => p.theme === 'extendable')
    const headers = patterns?.filter((p) => p?.slug?.includes('header'))
    const footers = patterns?.filter((p) => p?.slug?.includes('footer'))
    return { headers, footers }
}

const getTemplateParts = async () => {
    try {
        const parts = await fetch(
            window.extOnbData.wpRoot + 'wp/v2/template-parts',
            {
                headers: {
                    'Content-type': 'application/json',
                    'X-WP-Nonce': window.extOnbData.nonce,
                },
            },
        )
        if (!parts.ok) return
        return await parts.json()
    } catch (e) {
        // Fail gracefully for now
    }
}

export const getThemeVariations = async () => {
    try {
        const variations = await fetch(
            window.extOnbData.wpRoot +
                'wp/v2/global-styles/themes/extendable/variations',
            {
                headers: {
                    'Content-type': 'application/json',
                    'X-WP-Nonce': window.extOnbData.nonce,
                },
            },
        )
        if (!variations.ok) return
        return await variations.json()
    } catch (e) {
        // Fail gracefully for now
    }
}

export const updateThemeVariation = async (id, variation) => {
    try {
        const response = await fetch(
            `${window.extOnbData.wpRoot}wp/v2/global-styles/${id}`,
            {
                method: 'POST',
                headers: {
                    'Content-type': 'application/json',
                    'X-WP-Nonce': window.extOnbData.nonce,
                },
                body: JSON.stringify({
                    id,
                    settings: variation.settings,
                    styles: variation.styles,
                }),
            },
        )
        if (!response.ok) return
        return await response.json()
    } catch (e) {
        // Fail gracefully for now
    }
}
