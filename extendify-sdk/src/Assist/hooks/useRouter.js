import { useEffect, useLayoutEffect } from '@wordpress/element'
import { useState } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { home, tool, tip } from '@wordpress/icons'
import { Dashboard } from '@assist/pages/Dashboard'
import { Recommendations } from '@assist/pages/Recommendations'
import { Tasks } from '@assist/pages/Tasks'

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
        icon: tool,
        component: Tasks,
    },
    {
        slug: 'recommendations',
        name: __('Recommendations', 'extendify'),
        icon: tip,
        component: Recommendations,
    },
]
export const useRouter = () => {
    const [current, setCurrent] = useState()
    const Component = current?.component ?? (() => null)

    const navigateTo = (slug) => {
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
        handle()
        return () => {
            window.removeEventListener('hashchange', handle)
        }
    }, [current])
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
    }
}
