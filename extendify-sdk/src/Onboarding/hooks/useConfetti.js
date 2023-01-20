import { useEffect } from '@wordpress/element'
import confetti from 'canvas-confetti'

export const useConfetti = (config = {}, time = 0, ready = false) => {
    useEffect(() => {
        if (!ready) return
        const secondsFromNow = Date.now() + time
        const frame = () => {
            confetti({
                ...config,
                disableForReducedMotion: true,
                zIndex: 100000,
            })
            if (Date.now() < secondsFromNow) {
                // run every two frames
                requestAnimationFrame(() => {
                    requestAnimationFrame(frame)
                })
            }
        }
        frame()
    }, [config, time, ready])
}
