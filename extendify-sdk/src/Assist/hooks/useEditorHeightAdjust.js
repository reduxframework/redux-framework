import { useEffect, useRef } from '@wordpress/element'

/** Adjusts the editor height to fit the taskbar drop */
export const useEditorHeightAdjust = ({ open, leaveDelay = 0 }) => {
    const timerId = useRef(0)
    const previous = useRef(null)

    useEffect(() => {
        const editor = document.querySelector('.edit-post-layout')
        if (!open) return
        if (!editor) return
        clearTimeout(timerId.current)
        // Return if in full screen mode
        if (document.body.classList.contains('is-fullscreen-mode')) return
        // In case something is there, save it for when we close
        if (open) previous.current = editor.style
        const previousStyles = previous.current
        // Position it so it plays nicely with the expanding taskbar
        editor.style.position = 'absolute'
        editor.style.left = '0'
        editor.style.top = '0'
        editor.style.right = '0'
        return () => {
            const editor = document.querySelector('.edit-post-layout')
            timerId.current = setTimeout(() => {
                editor.style = previousStyles
            }, leaveDelay)
        }
    }, [open, leaveDelay])
}
