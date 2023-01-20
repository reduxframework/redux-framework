import { useEffect } from '@wordpress/element'
import { colord } from 'colord'
import useSWRImmutable from 'swr/immutable'
import { useGlobalSyncStore } from '@assist/state/GlobalSync'

export const useDesignColors = () => {
    const { designColors: globalDesignColors, setDesignColors } =
        useGlobalSyncStore()
    const { data: designColors } = useSWRImmutable('designColors', () => {
        // If we have partner colors, use those
        const documentStyles = window.getComputedStyle(document.documentElement)
        const partnerBg = documentStyles?.getPropertyValue(
            '--ext-partner-theme-primary-bg',
        )

        if (partnerBg) {
            return {
                mainColor: partnerBg,
                darkColor: colord(partnerBg).darken(0.1).toHex(),
                textColor:
                    documentStyles?.getPropertyValue(
                        '--ext-partner-theme-primary-text',
                    ) ?? '#fff',
            }
        }

        // Otherwise, use the admin colors
        const menu = document?.querySelector(
            'a.wp-has-current-submenu, li.current > a.current',
        )
        if (!menu) {
            return globalDesignColors
        }
        const adminColor = window.getComputedStyle(menu)?.['background-color']
        return {
            mainColor: adminColor,
            darkColor: colord(adminColor).darken(0.1).toHex(),
            textColor: '#fff',
        }
    })
    useEffect(() => {
        if (designColors?.mainColor) {
            document.documentElement.style.setProperty(
                '--ext-design-main',
                designColors.mainColor,
            )
        }
        if (designColors?.darkColor) {
            document.documentElement.style.setProperty(
                '--ext-design-dark',
                designColors.darkColor,
            )
        }
        if (designColors?.textColor) {
            document.documentElement.style.setProperty(
                '--ext-design-text',
                designColors.textColor,
            )
        }
        setDesignColors(designColors)
    }, [designColors, setDesignColors])

    return designColors || {}
}
