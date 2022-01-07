import { useRef, useEffect, useState } from '@wordpress/element'

export function useIsMounted() {
    const isMounted = useRef(false)

    useEffect(() => {
        isMounted.current = true
        return () => (isMounted.current = false)
    })
    return isMounted
}

export const useIsDevMode = () => {
    const [devMode, setDevMode] = useState(false)
    const handle = () => {
        setDevMode(window.location.search.indexOf('DEVMODE') > -1)
    }
    useEffect(() => {
        setDevMode(window.location.search.indexOf('DEVMODE') > -1)
        window.addEventListener('popstate', handle)
        return () => {
            window.removeEventListener('popstate', handle)
        }
    }, [])
    return devMode
}
