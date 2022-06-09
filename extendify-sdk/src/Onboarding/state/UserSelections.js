import create from 'zustand'
import { persist, devtools } from 'zustand/middleware'

const initialState = {
    siteType: {},
    siteInformation: {
        title: '',
    },
    style: null,
    pages: [],
    plugins: [],
    goals: [],
}
const store = (set, get) => ({
    ...initialState,
    setSiteType(siteType) {
        set({ siteType })
    },
    setSiteInformation(name, value) {
        const siteInformation = { ...get().siteInformation, [name]: value }
        set({ siteInformation })
    },
    has(type, item) {
        if (!item?.id) return false
        return get()[type].some((t) => t.id === item.id)
    },
    add(type, item) {
        if (get().has(type, item)) return
        set({ [type]: [...get()[type], item] })
    },
    remove(type, item) {
        set({ [type]: get()[type].filter((t) => t.id !== item.id) })
    },
    toggle(type, item) {
        if (get().has(type, item)) {
            get().remove(type, item)
            return
        }
        get().add(type, item)
    },
    setStyle(style) {
        set({ style })
    },
    canLaunch() {
        // The user can launch if they have a complete selection
        return (
            Object.keys(get()?.siteType ?? {})?.length > 0 &&
            Object.keys(get()?.style ?? {})?.length > 0 &&
            get()?.pages?.length > 0
        )
    },
    resetState() {
        set(initialState)
    },
})
export const useUserSelectionStore = create(
    persist(devtools(store), {
        name: 'extendify',
        getStorage: () => sessionStorage,
    }),
)
