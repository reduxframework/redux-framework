import { useEffect, useState } from '@wordpress/element'
import create from 'zustand'
import { devtools, persist } from 'zustand/middleware'
import { getTaskData, saveTaskData } from '../api/Tasks'

const state = (set, get) => ({
    // These are tests the user is in progress of completing.
    activeTests: [],
    // these are tasks the user has already completed
    completedTasks: [],
    isCompleted(task) {
        return get().completedTasks.includes(task)
    },
    completeTask(task) {
        if (get().isCompleted(task)) {
            return
        }
        set((state) => ({
            completedTasks: [...state.completedTasks, task],
        }))
    },
    uncompleteTask(task) {
        set((state) => ({
            completedTasks: state.completedTasks.filter((t) => t !== task),
        }))
    },
    toggleCompleted(task) {
        if (get().isCompleted(task)) {
            get().uncompleteTask(task)
            return
        }
        get().completeTask(task)
    },
})

const storage = {
    getItem: async () => JSON.stringify(await getTaskData()),
    setItem: async (_, value) => await saveTaskData(value),
    removeItem: () => undefined,
}

export const useTasksStore = create(
    persist(devtools(state, { name: 'Extendify Assist Tasks' }), {
        name: 'extendify-assist-tasks',
        getStorage: () => storage,
    }),
    state,
)

/* Hook useful for when you need to wait on the async state to hydrate */
export const useTasksStoreReady = () => {
    const [hydrated, setHydrated] = useState(useTasksStore.persist.hasHydrated)
    useEffect(() => {
        const unsubFinishHydration = useTasksStore.persist.onFinishHydration(
            () => setHydrated(true),
        )
        return () => {
            unsubFinishHydration()
        }
    }, [])
    return hydrated
}
