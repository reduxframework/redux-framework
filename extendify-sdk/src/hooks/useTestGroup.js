import { useState, useLayoutEffect } from '@wordpress/element'
import { useGlobalStore } from '@extendify/state/GlobalState'
import { useUserStore as user } from '@extendify/state/User'

export const useTestGroup = (key, options, override) => {
    const [group, setGroup] = useState()
    const ready = useGlobalStore((state) => state.ready)

    useLayoutEffect(() => {
        if (
            override ||
            (ready && !group) ||
            // Let the devbuild reset this
            window.extendifyData._canRehydrate
        ) {
            const testGroup = user.getState().testGroup(key, options)
            setGroup(testGroup)
        }
    }, [key, options, group, ready, override])

    return group
}
