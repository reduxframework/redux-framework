import { useEffect } from '@wordpress/element'

export const useWarnOnLeave = (enabled = true) => {
    // Display warning alert if user tries to exit
    useEffect(() => {
        if (!enabled) return
        const handleUnload = (event) => {
            event.preventDefault()
            return (event.returnValue = '')
        }
        const opts = { capture: true }
        window.addEventListener('beforeunload', handleUnload, opts)
        return () => {
            window.removeEventListener('beforeunload', handleUnload, opts)
        }
    }, [enabled])
}
