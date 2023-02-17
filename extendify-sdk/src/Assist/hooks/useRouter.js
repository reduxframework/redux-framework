import { useEffect, useLayoutEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { home } from '@wordpress/icons'
import create from 'zustand'
import { devtools } from 'zustand/middleware'
import { Dashboard } from '@assist/pages/Dashboard'
import { HelpCenter } from '@assist/pages/HelpCenter'
import { Recommendations } from '@assist/pages/Recommendations'
import { Tasks } from '@assist/pages/Tasks'
import { Tours } from '@assist/pages/Tours'
import {
    helpIcon,
    recommendationsIcon,
    tasksIcon,
    toursIcon,
} from '@assist/svg'

const pages = [
    {
        slug: 'dashboard',
        name: __('Dashboard', 'extendify'),
        icon: home,
        component: Dashboard,
    },
    {
        slug: 'tasks',
        name: __('Tasks', 'extendify'),
        icon: tasksIcon,
        component: Tasks,
    },
    {
        slug: 'tours',
        name: __('Tours', 'extendify'),
        icon: toursIcon,
        component: Tours,
    },
    {
        slug: 'recommendations',
        name: __('Recommendations', 'extendify'),
        icon: recommendationsIcon,
        component: Recommendations,
    },
    {
        slug: 'help-center',
        name: __('Help Center', 'extendify'),
        icon: helpIcon,
        component: HelpCenter,
    },
]
let onChangeEvents = []
const state = (set, get) => ({
    history: [],
    current: null,
    setCurrent: async (page) => {
        if (!page) return
        for (const event of onChangeEvents) {
            await event(page, { ...get() })
        }
        // If history is the same, dont add
        if (get().history[0]?.slug === page.slug) return
        set((state) => ({
            history: [page, ...state.history].filter(Boolean),
            current: page,
        }))
    },
})
const useRouterState = create(
    devtools(state, { name: 'Extendify Assist Router' }),
    state,
)
export const router = {
    onRouteChange: (event) => {
        // dont add if duplicate
        if (onChangeEvents.includes(event)) return
        onChangeEvents = [...onChangeEvents, event]
    },
    removeOnRouteChange: (event) => {
        onChangeEvents = onChangeEvents.filter((e) => e !== event)
    },
}

export const useRouter = () => {
    const { current, setCurrent, history } = useRouterState()
    const Component = current?.component ?? (() => null)

    const navigateTo = (slug) => {
        if (window.location.hash === `#${slug}`) {
            // Fire the event only
            window.dispatchEvent(new Event('hashchange'))
            return
        }
        window.location.hash = `#${slug}`
    }
    useLayoutEffect(() => {
        // if no hash is present use previous or add #dashboard
        if (!window.location.hash) {
            window.location.hash = `#${current?.slug ?? 'dashboard'}`
        }
    }, [current])

    useEffect(() => {
        // watch url changes for #dashboard, etc
        const handle = () => {
            const hash = window.location.hash.replace('#', '')
            const page = pages.find((page) => page.slug === hash)
            if (!page) {
                navigateTo(current?.slug ?? 'dashboard')
                return
            }
            setCurrent(page)
            // Update title to match the page
            document.title = `${page.name} | Extendify Assist`
        }
        window.addEventListener('hashchange', handle)
        if (!current) handle()
        return () => {
            window.removeEventListener('hashchange', handle)
        }
    }, [current, setCurrent])

    return {
        current,
        CurrentPage: () => (
            <div role="region" aria-live="polite">
                {/* Announce to SR on change */}
                <h1 className="sr-only">{current?.name}</h1>
                <Component />
            </div>
        ),
        pages,
        navigateTo,
        history,
    }
}
