import { Button } from '@wordpress/components'
import { useState } from '@wordpress/element'
import { useIsDevMode } from '@extendify/hooks/helpers'
import { useGlobalStore } from '@extendify/state/GlobalState'
import { useTaxonomyStore } from '@extendify/state/Taxonomies'
import { useTemplatesStore } from '@extendify/state/Templates'
import { useUserStore } from '@extendify/state/User'

export const DevSettings = () => {
    const [processing, setProcessing] = useState(false)
    const [canHydrate, setCanHydrate] = useState(false)
    const devMode = useIsDevMode()

    const handleReset = async () => {
        if (processing) return
        setProcessing(true)
        if (canHydrate) {
            setCanHydrate(false)
            useUserStore.setState({
                participatingTestsGroups: [],
            })
            await useUserStore.persist.rehydrate()
            window.extendifyData._canRehydrate = false
            setProcessing(false)
            return
        }
        useUserStore.persist.clearStorage()
        useGlobalStore.persist.clearStorage()
        await new Promise((resolve) => setTimeout(resolve, 1000))
        window.extendifyData._canRehydrate = true
        setCanHydrate(true)
        setProcessing(false)
    }

    const handleServerSwitch = async () => {
        const params = new URLSearchParams(window.location.search)
        params.delete('LOCALMODE', 1)
        params[params.has('DEVMODE') || devMode ? 'delete' : 'append'](
            'DEVMODE',
            1,
        )
        window.history.replaceState(
            null,
            null,
            window.location.pathname + '?' + params.toString(),
        )
        await new Promise((resolve) => setTimeout(resolve, 500))
        window.dispatchEvent(new Event('popstate'))
        useTemplatesStore.getState().resetTemplates()
        useTemplatesStore.getState().updateSearchParams({})
        useTaxonomyStore.persist.clearStorage()
        useTaxonomyStore.persist.rehydrate()
        useTemplatesStore.setState({
            taxonomyDefaultState: {},
        })
        useTaxonomyStore
            .getState()
            .fetchTaxonomies()
            .then(() => {
                useTemplatesStore.getState().setupDefaultTaxonomies()
            })
    }

    if (!window.extendifyData.devbuild) return null

    return (
        <section className="p-6 flex flex-col space-y-6 border-l-8 border-extendify-secondary">
            <div>
                <p className="text-base m-0 text-extendify-black">
                    Development Settings
                </p>
                <p className="text-sm italic m-0 text-gray-500">
                    Only available on dev builds
                </p>
            </div>
            <div className="flex space-x-2">
                <Button isSecondary onClick={handleServerSwitch}>
                    Switch to {devMode ? 'Live' : 'Dev'} Server
                </Button>
                <Button isSecondary onClick={handleReset}>
                    {processing
                        ? 'Processing...'
                        : canHydrate
                        ? 'OK! Press to rehydrate app'
                        : 'Reset User Data'}
                </Button>
            </div>
        </section>
    )
}
