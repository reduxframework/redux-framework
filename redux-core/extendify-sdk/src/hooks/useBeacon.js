import { useEffect } from '@wordpress/element'

export default function useBeacon(show) {
    const showBeacon = () => {
        const container = document.getElementById('beacon-container')
        if (container) {
            container.style.position = 'relative'
            container.style.zIndex = Number.MAX_SAFE_INTEGER
            container.style.display = 'block'
        }
    }

    const hideBeacon = () => {
        const container = document.getElementById('beacon-container')
        if (container) {
            container.style.display = 'none'
            window.Beacon('close')
        }
    }

    useEffect(() => {
        if (!show) {
            return
        }

        if (window.Beacon) {
            showBeacon()
            return () => hideBeacon()
        }

        // Code direct from HS
        (function (
            e, t, n,
        ) {
            function a() {
                const e = t.getElementsByTagName('script')[0],
                    n = t.createElement('script')
            ;(n.async = !0), (n.src = 'https://beacon-v2.helpscout.net'), e.parentNode?.insertBefore(n, e)
            }
            if (
                ((e.Beacon = n = function (
                    t, n, a,
                ) {
                    e.Beacon.readyQueue.push({
                        method: t, options: n, data: a,
                    })
                }),
                (n.readyQueue = []),
                'complete' === t.readyState)
            )
                return a()
            e.attachEvent
                ? e.attachEvent('onload', a)
                : e.addEventListener(
                    'load', a, !1,
                )
        })(
            window, document, window.Beacon || function () {},
        )

        window.Beacon('init', '2b8c11c0-5afc-4cb9-bee0-a5cb76b2fc91')
        window.Beacon(
            'on', 'ready', showBeacon,
        )
        return () => {
            window.Beacon(
                'off', 'ready', showBeacon,
            )
            hideBeacon()
        }
    }, [show])

}
