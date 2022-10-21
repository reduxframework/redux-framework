import { useLayoutEffect, useState } from '@wordpress/element'
import { colord } from 'colord'

export const useAdminColors = () => {
    const [mainColor, setMainColor] = useState('#3959e9')
    const [darkColor, setDarkColor] = useState(
        colord('#3959e9').darken(0.05).toHex(),
    )
    useLayoutEffect(() => {
        const menu = document.querySelector('a.wp-has-current-submenu')
        if (!menu) return
        const adminColor = window.getComputedStyle(menu)?.['background-color']
        setMainColor(adminColor)
        setDarkColor(colord(adminColor).darken(0.05).toHex())
    }, [])
    return { mainColor, darkColor }
}
