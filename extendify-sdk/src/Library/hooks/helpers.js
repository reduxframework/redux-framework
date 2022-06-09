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
    const check = () => {
        return (
            window.location.search.indexOf('DEVMODE') > -1 ||
            window.location.search.indexOf('LOCALMODE') > -1
        )
    }
    useEffect(() => {
        const handle = () => setDevMode(check())
        handle()
        window.addEventListener('popstate', handle)
        return () => {
            window.removeEventListener('popstate', handle)
        }
    }, [])
    return devMode
}

/** Dev debugging tool to identify leaky renders: https://usehooks.com/useWhyDidYouUpdate/ */
export const useWhyDidYouUpdate = (name, props) => {
    const previousProps = useRef()
    useEffect(() => {
        if (previousProps.current) {
            const allKeys = Object.keys({ ...previousProps.current, ...props })
            const changesObj = {}
            allKeys.forEach((key) => {
                if (previousProps.current[key] !== props[key]) {
                    changesObj[key] = {
                        from: previousProps.current[key],
                        to: props[key],
                    }
                }
            })
            if (Object.keys(changesObj).length) {
                console.log('[why-did-you-update]', name, changesObj)
            }
        }
        previousProps.current = props
    })
}
