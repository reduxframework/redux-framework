import { colord } from 'colord'

export const adminBgColor = () => {
    const menu = document?.querySelector('a.wp-has-current-submenu')
    if (!menu) return '#1e1e1e'
    return window.getComputedStyle(menu)?.['background-color'] || '#1e1e1e'
}
export const adminTextColor = () => {
    const menu = document?.querySelector('a.wp-has-current-submenu')
    if (!menu) return '#fff'
    return window.getComputedStyle(menu)?.['color'] || '#fff'
}
export const assistAdminBarBgColor = () => {
    const adminBar = document?.querySelector('#wpadminbar')
    if (!adminBar) return '#1e1e1e'
    const computed = window.getComputedStyle(adminBar)?.['background-color']
    if (!computed) return '#1e1e1e'
    return colord(computed).isDark() ? adminBgColor() : computed
}
