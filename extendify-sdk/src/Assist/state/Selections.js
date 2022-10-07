import { useEffect, useState } from '@wordpress/element'
import create from 'zustand'
import { devtools, persist } from 'zustand/middleware'
import { getUserSelectionData, saveUserSelectionData } from '@assist/api/Data'

const state = () => ({
    siteType: {},
    siteInformation: {
        title: undefined,
    },
    feedbackMissingSiteType: '',
    feedbackMissingGoal: '',
    exitFeedback: undefined,
    siteTypeSearch: [],
    style: null,
    pages: [],
    plugins: [],
    goals: [],
})

// This checks the local storage cache for the user selections set in Launch, if any
const cachedSelections = localStorage.getItem('extendify-site-selection')
const storage = {
    getItem: cachedSelections
        ? () => {
              localStorage.removeItem('extendify-site-selection')
              return cachedSelections
          }
        : async () => JSON.stringify(await getUserSelectionData()),
    setItem: async (_, value) => await saveUserSelectionData(value),
    removeItem: () => undefined,
}

export const useSelectionStore = create(
    persist(devtools(state, { name: 'Extendify User Selections' }), {
        name: 'extendify-site-selection',
        getStorage: () => storage,
    }),
    state,
)

/* Hook useful for when you need to wait on the async state to hydrate */
export const useSelectionStoreReady = () => {
    const [hydrated, setHydrated] = useState(
        useSelectionStore.persist.hasHydrated,
    )
    useEffect(() => {
        const unsubFinishHydration =
            useSelectionStore.persist.onFinishHydration(() => setHydrated(true))
        return () => {
            unsubFinishHydration()
        }
    }, [])
    return hydrated
}
