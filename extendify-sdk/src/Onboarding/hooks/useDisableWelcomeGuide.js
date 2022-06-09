import { useSelect, dispatch } from '@wordpress/data'
import { useEffect } from '@wordpress/element'

export const useDisableWelcomeGuide = () => {
    const isWelcomeGuide = useSelect((select) => {
        return select('core/edit-post').isFeatureActive('welcomeGuide')
    }, [])

    useEffect(() => {
        if (isWelcomeGuide) {
            dispatch('core/edit-post').toggleFeature('welcomeGuide')
        }
    }, [isWelcomeGuide])
}
