import { useRef, useEffect } from '@wordpress/element'

export function useIsMounted() {
    const isMounted = useRef(false)

    useEffect(() => {
        isMounted.current = true
        return () => isMounted.current = false
    })
    return isMounted
}
