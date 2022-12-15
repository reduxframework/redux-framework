import { useEffect } from '@wordpress/element'
import { colord } from 'colord'
import useSWRImmutable from 'swr/immutable'

export const useAdminColors = () => {
    const { data: adminColors } = useSWRImmutable('adminColors', () => {
        const menu = document?.querySelector(
            'a.wp-has-current-submenu, li.current > a.current',
        )
        if (!menu) return null
        const adminColor = window.getComputedStyle(menu)?.['background-color']
        return {
            mainColor: adminColor,
            darkColor: colord(adminColor).darken(0.5).toHex(),
        }
    })
    useEffect(() => {
        if (adminColors?.mainColor) {
            document.documentElement.style.setProperty(
                '--wp-admin-theme-color',
                adminColors.mainColor,
            )
        }
    }, [adminColors])

    return adminColors || {}
}
