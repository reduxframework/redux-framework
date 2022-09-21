import { useEffect } from '@wordpress/element'

export const useAnimatedHeight = (preview, blockHeight, ready) => {
    useEffect(() => {
        let raf1, raf2
        if (!preview.current) return
        const p = preview.current
        const iframe = p?.querySelector('iframe[title]')
        if (!iframe) return
        const content = iframe.contentWindow.document.body
        const scale = preview.offsetWidth / 1400
        iframe.style.maxHeight = `${blockHeight / scale}px`

        const handleIn = () => {
            if (!content?.offsetHeight) return
            // The live component changes over time so easier to query on demand
            const height = content.offsetHeight
            content.style.transitionDuration = Math.max(height * 3, 3000) + 'ms'
            raf1 = window.requestAnimationFrame(() => {
                content.style.top = Math.abs(height - blockHeight) * -1 + 'px'
            })
        }
        const handleOut = () => {
            if (!content?.offsetHeight) return
            const height = content.offsetHeight
            content.style.transitionDuration = height / 1.5 + 'ms'
            raf2 = window.requestAnimationFrame(() => {
                content.style.top = 0
            })
        }

        p.addEventListener('focus', handleIn)
        p.addEventListener('mouseenter', handleIn)
        p.addEventListener('blur', handleOut)
        p.addEventListener('mouseleave', handleOut)
        return () => {
            window.cancelAnimationFrame(raf1)
            window.cancelAnimationFrame(raf2)
            p.removeEventListener('focus', handleIn)
            p.removeEventListener('mouseenter', handleIn)
            p.removeEventListener('blur', handleOut)
            p.removeEventListener('mouseleave', handleOut)
        }
    }, [blockHeight, preview, ready])
}
