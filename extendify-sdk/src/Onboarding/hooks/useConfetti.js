import { useEffect } from '@wordpress/element'
import confetti from 'canvas-confetti'

export const useConfetti = (config = {}, time = 0) => {
    useEffect(() => {
        const secondsFromNow = Date.now() + time
        const frame = () => {
            confetti({
                ...config,
                disableForReducedMotion: true,
                zIndex: 100000,
            })
            if (Date.now() < secondsFromNow) {
                requestAnimationFrame(frame)
            }
        }
        frame()
    }, [config, time])
}
