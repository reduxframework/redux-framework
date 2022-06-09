import { useRef, useEffect, useLayoutEffect } from '@wordpress/element'

export const useIsMounted = () => {
    const isMounted = useRef(false)

    useEffect(() => {
        isMounted.current = true
        return () => (isMounted.current = false)
    })
    return isMounted
}
export const useIsMountedLayout = () => {
    const isMounted = useRef(false)

    useLayoutEffect(() => {
        isMounted.current = true
        return () => (isMounted.current = false)
    })
    return isMounted
}
